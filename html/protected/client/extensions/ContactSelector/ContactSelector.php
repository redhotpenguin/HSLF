<?php

class ContactSelector extends CInputWidget {

    public $options = array();

    public function init() {
        
    }

    public function run() {
        $contacts = $this->model->contacts;
        
        
        $this->render('contact_selector', array('contacts' => $contacts, 'model'=>$this->model));
    }

}