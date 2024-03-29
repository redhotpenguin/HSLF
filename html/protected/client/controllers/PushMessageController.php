<?php

Yii::import("backend.vendors.UrbanAirship.*", false);

use UrbanAirship\PushClient as PushClient;
use UrbanAirship\ReportClient as ReportClient;
use UrbanAirship\SegmentClient as SegmentClient;
use UrbanAirship\lib\PushNotification as PushNotification;

class PushMessageController extends CrudController {

    private $pushClient;
    private $segmentClient;
    private $reportClient;

    public function __construct() {
        parent::__construct('pushMessage');

        $tenant = Yii::app()->user->getLoggedInUserTenant();
        $this->pushClient = new PushClient($tenant->ua_api_key, $tenant->ua_api_secret);
        $this->segmentClient = new SegmentClient($tenant->ua_api_key, $tenant->ua_api_secret);
        $this->reportClient = new ReportClient($tenant->ua_api_key, $tenant->ua_api_secret);

        $pushMessage = new PushMessage;

        $pushMessage->creation_date = date('Y-m-d H:i:s');

        $this->setModel($pushMessage);
        $this->setFriendlyModelName('Push Message');

        $extraRules = array(
            array('allow',
                'actions' => array('composer', 'view', 'detail', 'confirmation', 'jsonSegments', 'jsonSegment', 'jsonReport', 'exportCSV'),
                'roles' => array('managePushMessages'),
            )
        );

        $this->setExtraRules($extraRules);
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        if (isset($postData['PushMessage']['tags']))
            $model->massUpdateTags($postData['PushMessage']['tags']);
        else
            $model->removeAllTagsAssociation();
    }

// @todo: refactor
    public function actionComposer() {

        $pushMessage = new PushMessage();
        $pushMessage->recipient_type = 'broadcast'; // default recipient type
        $payload = new Payload('composer');
        $id = null;

        if (Yii::app()->request->isPostRequest && isset($_POST['recipient_type'])) {

            $recipientType = $_POST['recipient_type'];

            if (!in_array($recipientType, array('broadcast', 'tag', 'segment', 'single'))) { // todo: move allowed segment types to PushMessage model
                throw new CHttpException(500, "Invalid recipient type");
            }

            $pushMessage->attributes = $_POST['PushMessage'];
            $payload->attributes = $_POST['Payload'];

            $pushMessage->creation_date = date('Y-m-d H:i:s');
            $pushMessage->payload_id = 0;
            $pushMessage->recipient_type = $recipientType;


            if ($_POST['Payload']['type'] == 'post') {
                $payload->scenario = 'type_post';
            } elseif ($_POST['Payload']['type'] == 'share') {
                $payload->scenario = 'type_share';
            } else {
                $payload->title = $pushMessage->alert;
                $payload->scenario = 'insert';
            }

            $pushMessage->validate();
            $payload->validate();


            if (!$pushMessage->errors && !$payload->errors) {

                $transaction = $payload->dbConnection->beginTransaction();

                try {
                    $payload->save();

                    $pushMessage->payload_id = $payload->id;
                    $pushMessage->save();

                    if ($recipientType === 'tag') {

                        if (isset($_POST['PushMessage']['tags'])) {
                            $pushMessage->massUpdateTags($_POST['PushMessage']['tags']); // @WARNING - todo: make sure tags contains legit data
                        } else {
                            throw new Exception("At least one tag must be present.");
                        }
                    } elseif ($recipientType == 'segment') {
                        $id = $_POST['segment_id'];
// retrieve tags from the segment and assign them to the push message

                        $segment = $this->segmentClient->getSegment($id);

                        $segmentTags = $segment->getTags();

                        foreach ($segmentTags as $segmentTag) {
                            if (( $tagId = Tag::model()->getTagId($segmentTag))) {
                                $pushMessage->addTagAssociation($tagId);
                            } else {
                                $tag = new Tag();
                                $tag->type = 'alert';
                                $tag->display_name = ucfirst(str_replace("_", " ", $segmentTag));
                                $tag->name = $segmentTag;

                                if ($tag->validate()) {
                                    $tag->save();
                                    $pushMessage->addTagAssociation($tag->id);
                                }
                            }
                        }
                    } else {
                        $id = $_POST['device_id'];
                    }

                    $pushMessage->push_identifier = $this->sendPushMessage($pushMessage, $recipientType, $id);

                    $pushMessage->save();
                    $transaction->commit();

                    $this->redirect(array('confirmation', 'pushMessageId' => $pushMessage->id));
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', $e->getMessage());
                }
            }
        }

        $data = array(
            'pushMessage' => $pushMessage,
            'payload' => $payload,
            'tagTypes' => Tag::model()->getAllowedTypes(),
            'device_id' => isset($_POST['device_id']) ? $_POST['device_id'] : ""
        );

        $this->render('composer', $data);
    }

    public function actionConfirmation($pushMessageId) {
        $pushMessage = $this->loadModel($pushMessageId);

        $this->render('confirmation', array('pushMessage' => $pushMessage));
    }

    public function actionView($id) {
        $pushMessage = $this->loadModel($id);
        $this->render('view', array('pushMessage' => $pushMessage));
    }

    private function sendPushMessage(PushMessage $pushMessage, $method, $id = null) {

        $pushNotification = new PushNotification($pushMessage->alert);

        if ($pushMessage->payload->type != 'other') {

            if ($pushMessage->payload->type == 'post') {
                $legacyPayload = '{ "type": "post", "data": { "id": ' . $pushMessage->payload->post_number . ' } }';
            } else {
                $legacyPayload = '{ "type": "share", "data": { "id": ' . $pushMessage->payload->id . ' } }';
            }

            $payload = array(
                'payload_id' => (string) $pushMessage->payload->id,
                'payload' => $legacyPayload // old payload format (2.0 apps..)
            );
            $pushNotification->setPayload($payload);
        }

        switch ($method) {
            case "tag":
                $tags = array();
                foreach ($pushMessage->tags as $tag) {
                    array_push($tags, $tag->name);
                }
                $pushId = $this->pushClient->sendPushNotificationByTags($pushNotification, $tags);
                break;

            case "broadcast":
                $pushId = $this->pushClient->sendBroadcastPushNotification($pushNotification);
                break;

            case "segment":
                $pushId = $this->pushClient->sendPushNotificationBySegment($pushNotification, $id);
                break;

            case "single":
                $pushId = $this->pushClient->sendPushNotificationToDevice($pushNotification, $id);
                break;

            default: throw new Exception("method not supported");
        }


        return $pushId;
    }

    public function actionJsonSegments() {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        $segments = $this->segmentClient->getSegments();
        $response = array();
        foreach ($segments as $segment) {
            array_push($response, array('id' => $segment->getId(), 'display_name' => $segment->getDisplayName()));
        }

        echo CJSON::encode($response);
        Yii::app()->end();
    }

    public function actionJsonSegment($segmentId) {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        $segment = $this->segmentClient->getSegment($segmentId);

        $response = array(
            'id' => $segment->getId(),
            'name' => $segment->getDisplayName(),
            'criteria' => $segment->getCriteria()
        );

        echo CJSON::encode($response);
        Yii::app()->end();
    }

    public function actionJsonReport($pushId) {
        header('Content-type: ' . 'application/json;charset=UTF-8');

        $response = $this->reportClient->getPushReport($pushId);

        echo CJSON::encode($response);
        Yii::app()->end();
    }

}