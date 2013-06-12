<?php

Yii::import("backend.vendors.UrbanAirship.*", true);

class PushMessageController extends CrudController {

    public function __construct() {
        parent::__construct('pushMessage');

        $pushMessage = new PushMessage;

        $pushMessage->creation_date = date('Y-m-d h:i:s');

        $this->setModel($pushMessage);
        $this->setFriendlyModelName('Push Message');

        $extraRules = array(
            array('allow',
                'actions' => array('composer', 'view', 'detail', 'confirmation'),
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

    public function actionComposer() {
        $pushMessage = new PushMessage();
        $payload = new Payload();
        $unfilterdTagIds = array();

        if (Yii::app()->request->isPostRequest && isset($_POST['recipient_type'])) {

            $recipientType = $_POST['recipient_type'];

            if ($recipientType !== 'broadcast' && $recipientType !== 'tag') {
                throw new CHttpException(500, "Invalid recipient type");
            }

            $pushMessage->attributes = $_POST['PushMessage'];
            $payload->attributes = $_POST['Payload'];

            $pushMessage->creation_date = date('Y-m-d h:i:s');
            $pushMessage->payload_id = 0;
            $pushMessage->recipient_type = $recipientType;
            
            $pushMessage->validate();
            $payload->validate();

            if (!$pushMessage->errors && !$payload->errors) {

                $transaction = $payload->dbConnection->beginTransaction();

                try {
                    $payload->save();

                    $pushMessage->payload_id = $payload->id;

                    $pushMessage->save();

                    if ($recipientType === 'tag') {

                        if(isset($_POST['TagIds'])) {
                            $unfilterdTagIds = array_unique($_POST['TagIds']);
                            $pushMessage->massUpdateTags($unfilterdTagIds); // @WARNING - todo: make sure $unfilterdTagIds contains legit data
                        } else {
                            throw new Exception("At least one tag must be present.");
                        }
                    }

                    $this->sendPushMessage($pushMessage, $recipientType);

                    $transaction->commit();
                    $this->redirect(array('confirmation', 'pushMessageId' => $pushMessage->id));
                } catch (Exception $e) {
                    $transaction->rollback();
                    Yii::app()->user->setFlash('error', $e->getMessage());
                }
            }
        }

        $data = array(
            'tags' => Tag::model()->findAll(),
            'pushMessage' => $pushMessage,
            'payload' => $payload
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

    private function sendPushMessage(PushMessage $pushMessage, $method) {
        
        return 1234;

        $tenant = Yii::app()->user->getLoggedInUserTenant();

        $client = new PushClient($tenant->ua_api_key, $tenant->ua_api_secret);
        $payload = array();
        $tags = array();

        if ($pushMessage->payload->type != 'other') {
            $payload = array('payload_id' => (string) $pushMessage->payload->id);
        }

        foreach ($pushMessage->tags as $tag) {
            array_push($tags, $tag->name);
        }

        $pushNotification = new PushNotification($pushMessage->alert);
        $pushNotification->setPayload($payload);


        if ($method == 'tag') {
            $result = $client->sendPushNotificationByTags($pushNotification, $tags);
        } else { // broadcast
            $result = $client->sendBroadcastPushNotification($pushNotification);
        }


        return $result;
    }

}