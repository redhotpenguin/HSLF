<?php

class RecommendationController extends CrudController {

    public function __construct() {
        parent::__construct('recommendation');
        $this->setModel( new Recommendation );
        $this->setFriendlyModelName('Recommendation');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }

    protected function renderData() {
        return array();
    }

}