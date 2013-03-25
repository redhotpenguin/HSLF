<?php

class ScorecardItemController extends CrudController {

    public function __construct() {
        parent::__construct('scorecardItem');
        $this->setModelName('ScorecardItem');
        $this->setFriendlyModelName('Scorecard Item');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

    }

    protected function renderData() {
        return array();
    }

}