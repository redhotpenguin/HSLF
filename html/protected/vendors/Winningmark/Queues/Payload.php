<?php


class Payload {

    /**
     * push notification
     * @var string
     */
    protected $alert;

    /**
     * array of search attribute
     * @var array 
     */
    protected $searchAttributes = array();



    /**
     * array of key/value pairs
     * @var array 
     */
    protected $extra = array();

    /**
     * Payload
     * @param string $alert alert to send
     * @param array $searchAttributes search attributes
     * @param array $extra custom data
     */
    public function __construct($alert, array $searchAttributes = null, array $extra = null) {

        if (empty($alert))
            throw new \InvalidArgumentException("An alert is required");


        $this->alert = $alert;
        $this->searchAttributes = $searchAttributes;
        $this->extra = $extra;
    }

    /**
     * Get the push notification (alert)
     * @return string alert
     */
    public function getAlert() {
        return $this->alert;
    }

    /**
     * Get Search attributes
     * return array tokens
     */
    public function getSearchAttributes() {
        return $this->searchAttributes;
    }

    /**
     * Get custom data
     * return array of custom data
     */
    public function getExtra() {
        return $this->extra;
    }

}