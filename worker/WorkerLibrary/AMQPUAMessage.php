<?php

namespace WorkerLibrary;

/**
 * AMQP urbanairship - message structure - UTF8 should be used
 *
 * @author jonas
 */
class AMQPUAMessage {

    /**
     * Client Name
     * @var string 
     */
    public $clientName;

    /**
     * Client Email
     * @var string 
     */
    public $clientEmail;

    /**
     * Urban Airship API Key
     * @var string 
     */
    public $apiKey;

    /**
     * Urban Airship API Secret
     * @var string 
     */
    public $apiSecret;

    /**
     * array of tokens (ios)
     * @var array 
     */
    public $tokens = array();

    /**
     * array of apids (androids)
     * @var array 
     */
    public $apids = array();

    /**
     * array of key/value pairs
     * @var array 
     */
    public $customData = array();

    /**
     * Helper - Unserialize a message
     * @param string  $serializedMsg serialized messsage
     * @return AMQPUAMessage AMQPUAMessage object
     */
    public static function unserialize($serializedMsg) {
        $amqpUaMessage = unserialize($serializedMsg);
        if ($amqpUaMessage == false) {
            throw new Exception("could not deserialize message");
        } else {
            return $amqpUaMessage;
        }
    }

    /**
     * Helper - serialize a message
     * @return string serialized AMQPUAMessage object
     */
    public function serialize() {
        return serialize($this);
    }

}

