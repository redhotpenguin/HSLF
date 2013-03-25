<?php

class BallotItemController extends CrudController {

    public function __construct() {
        parent::__construct('ballotItem');
        $this->setModelName('BallotItem');
        $this->setFriendlyModelName('Ballot Item');
    }


    protected function afterSave(CActiveRecord $model, $postData = array()) {
        
    }
}
