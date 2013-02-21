<?php

class ContactSelector extends CInputWidget {

    public $options = array();

    public function init() {
        
    }

    public function run() {
        $this->render('contact_selector');
    }

}