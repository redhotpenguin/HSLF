<?php

class PushNotification {

    private $tags = array();
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

    public function getTags() {
        return $this->tags;
    }

    public function setTags(array $tags) {
        $this->tags = $tags;
    }

    public function getPayload() {
        return $this->payload;
    }

    public function setPayload(array $payload) {
        $this->payload = $payload;
    }

}