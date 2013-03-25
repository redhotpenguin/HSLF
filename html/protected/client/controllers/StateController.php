<?php

class StateController extends CrudController {

    public function __construct() {
        parent::__construct('state');
        $this->setModelName('State');
        $this->setFriendlyModelName('State');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}