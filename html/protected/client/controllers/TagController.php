<?php

class TagController extends CrudController {

    public function __construct() {
        parent::__construct('tag');
        $this->setModelName('Tag');
        $this->setFriendlyModelName('Tag');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}
