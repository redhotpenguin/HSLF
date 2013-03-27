<?php

class PartyController extends CrudController {

    public function __construct() {
        parent::__construct('party');
        $this->setModel( new Party );
        $this->setFriendlyModelName('Party');
    }

}