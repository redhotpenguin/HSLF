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
                'actions' => array('composer'),
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

        $data = array(
            'tags' => Tag::model()->findAll(),
            'pushMessage' => $pushMessage,
            'payload' => $payload
        );

        $this->render('composer', $data);
    }

}