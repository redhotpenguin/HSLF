<?php

class OptionController extends CrudController {

    public function __construct() {
        parent::__construct('option');
        $this->setModel(new Option );
        $this->setFriendlyModelName('Option');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}
