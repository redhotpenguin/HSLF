<?php

/**
 * Represent a push message containg an alert and a payload
 * @todo: make sure total push message byte size doesn't exceed allowed limit.
 */
class PushNotification {

    private $payload = array();
    private $alert;
    
    
    public function __construct($alert){
        if(!$alert){
            throw new InvalidArgumentException('Alert is missing.');
        }
        $this->alert = $alert;
    }
    
    public function getAlert(){
        return $this->alert;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function setPayload(array $payload) {
        $this->payload = $payload;
    }

}