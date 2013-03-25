<?php

class PartyController extends CrudController {

    public function __construct() {
        parent::__construct('party');
        $this->setModel( new Party );
        $this->setFriendlyModelName('Party');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}