<?php

class OptionController extends CrudController {

    public function __construct() {
        parent::__construct('option');
        $this->setModel( new Option );
        $this->setFriendlyModelName('Option');
    }

}
