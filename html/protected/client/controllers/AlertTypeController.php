<?php

class AlertTypeController extends CrudController {
    
    
    public function __construct(){
        parent::__construct('alertType');
        $alertType = new AlertType;
        $alertType->category = 'alert';
        $this->setModel($alertType);
        $this->setFriendlyModelName('Alert Type');
    }
}
