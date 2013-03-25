<?php

class OfficeController extends CrudController {

    public function __construct() {
        parent::__construct('office');
        $this->setModelName('Office');
        $this->setFriendlyModelName('Office');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}