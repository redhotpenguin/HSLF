<?php

class AlertTypeController extends CrudController {
    
    
    public function __construct(){
        parent::__construct('alertType');
        $this->setModel(new AlertType);
        $this->setFriendlyModelName('Alert Type');
    }
}
