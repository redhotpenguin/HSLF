<?php

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

        if (Yii::app()->request->isPostRequest) {
            $pushMessage->attributes = $_POST['PushMessage'];
            $payload->attributes = $_POST['Payload'];

            $pushMessage->creation_date = date('Y-m-d h:i:s');
            $pushMessage->payload_id = 0;

            if (isset($_POST['TagIds'])) {
                $unfilterdTagIds = array_unique($_POST['TagIds']);
            }

            $pushMessage->validate();
            $payload->validate();

            if (!$pushMessage->errors && !$payload->errors) {

                $transaction = $payload->dbConnection->beginTransaction();

                try {
                    $payload->save();

                    $pushMessage->payload_id = $payload->id;

                    $pushMessage->save();

                    if (!empty($unfilterdTagIds)) {
                        $pushMessage->massUpdateTags($unfilterdTagIds); // @WARNING - todo: make sure $unfilterdTagIds contains legit data
                    }
                    
                    $pushMessage->deliverPush();
                    
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

}