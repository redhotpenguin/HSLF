<?php

class AlertTypeController extends CrudController {
    
    
    public function __construct(){
        parent::__construct('alertType');
        $this->setModelName('AlertType');
        $this->setFriendlyModelName('Alert Type');
    }

}
