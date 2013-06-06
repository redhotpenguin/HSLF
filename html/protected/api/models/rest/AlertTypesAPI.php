<?php

class AlertTypesAPI extends APIBase implements IAPI {

    public function __construct(){
        parent::__construct( new AlertType );
    }

    
    public function requiresAuthentification() {
        //return true;
    }
}