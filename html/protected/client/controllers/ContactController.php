<?php

class ContactController extends CrudController {

    public function __construct() {
        parent::__construct('contact');
        $this->setModel(new Contact);
        $this->setFriendlyModelName('Contact');
    }

}