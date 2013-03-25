<?php

class PushMessageController extends CrudController {

    public function __construct() {
        parent::__construct('pushMessage');
        $this->setModelName('PushMessage');
        $this->setFriendlyModelName('Push Message');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

        if (isset($postData['PushMessage']['tags']))
            $model->massUpdateTags($postData['PushMessage']['tags']);
        else
            $model->removeAllTagsAssociation();
    }

    protected function renderData() {
        return array();
    }

}