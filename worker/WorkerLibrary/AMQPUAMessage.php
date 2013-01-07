<?php

namespace WorkerLibrary;

/**
 * AMQP urbanairship - message structure
 *
 * @author jonas
 */
class AMQPUAMessage {

    public $tokens = array();
    public $apids = array();
    public $customData = array();

    
    // helpers
    public static function unserialize($serializedMsg) {
        $amqpUaMessage = unserialize($serializedMsg);
        if ($amqpUaMessage == false) {
            throw new Exception("could not deserialize message");
        } else {
            return $amqpUaMessage;
        }
    }
    
    public function serialize(){
        return serialize($this);
    }

}

