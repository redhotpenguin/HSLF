<?php

class VoteController extends CrudController {

    public function __construct() {
        parent::__construct('vote');
        $this->setModelName('Vote');
        $this->setFriendlyModelName('Vote');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

    }

    protected function renderData() {
        return array();
    }
}
