<?php

class ScorecardItemController extends CrudController {

    public function __construct() {
        parent::__construct('scorecardItem');
        $this->setModel(new ScorecardItem );
        $this->setFriendlyModelName('Scorecard Item');
    }

    protected function afterSave(CActiveRecord $model, $postData = array()) {

    }

    protected function renderData() {
        return array();
    }

}