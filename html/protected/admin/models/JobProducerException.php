<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JobProducerException
 *
 * @author jonas
 */
class JobProducerException extends Exception {

    const CONNECTION_ERROR = "Could not connect to the broker.";
    const QUEUE_ERROR = "Could not create a queue";
    const EXCHANGE_ERROR = "Could not get an exchange";
    const BIND_ERROR = "Could not bind the queue to the exchange";

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        // some code
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

}

?>
