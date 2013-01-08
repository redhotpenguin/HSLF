<?php

namespace WorkerLibrary;

class Payload {

    /**
     * push notification
     * @var string
     */
    protected $alert;

    /**
     * array of tokens (ios)
     * @var array 
     */
    protected $tokens = array();

    /**
     * array of apids (androids)
     * @var array 
     */
    protected $apids = array();

    /**
     * array of key/value pairs
     * @var array 
     */
    protected $extra = array();

    /**
     * Payload
     * @param string $alert alert to send
     * @param array $tokens ios tokens
     * @param array $apids android apids
     * @param array $extra custom data
     */
    public function __construct($alert, array $tokens = null, array $apids = null, array $extra = null) {
        $this->alert = $alert;
        $this->tokens = $tokens;
        $this->apids = $apids;
        $this->extra = $extra;
    }
    
    
    /**
     * Get the push notification (alert)
     * @return string alert
     */
    public function getAlert(){
        return $this->alert;
    }

    /**
     * Get Tokens
     * return array tokens
     */
    public function getTokens() {
        return $this->tokens;
    }

    /**
     * Get Apids
     * return array tokens
     */
    public function getApids() {
        return $this->apids;
    }

    /**
     * Get custom data
     * return array tokens
     */
    public function getExtra() {
        return $this->tokens;
    }

}