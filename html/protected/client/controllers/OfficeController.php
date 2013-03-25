<?php

class OfficeController extends CrudController {

    public function __construct() {
        parent::__construct('office');
        $this->setModel(new Office);
        $this->setFriendlyModelName('Office');
    }

}