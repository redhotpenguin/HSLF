<?php

class ContactController extends CrudController {

    public function __construct() {
        parent::__construct('contact');
        $this->setModelName('Contact');
        $this->setFriendlyModelName('Contact');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

    }

    protected function renderData() {
        return array();
    }

}