<?php

class PushMessageController extends CrudController {

    public function __construct() {
        parent::__construct('pushMessage');

        $pushMessage = new PushMessage;

        $pushMessage->creation_date = date('Y-m-d h:i:s');

        $this->setModel($pushMessage);
        $this->setFriendlyModelName('Push Message');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

        if (isset($postData['PushMessage']['tags']))
            $model->massUpdateTags($postData['PushMessage']['tags']);
        else
            $model->removeAllTagsAssociation();
    }

}