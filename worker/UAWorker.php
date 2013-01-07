<?php

include_once("bootstrap.php");

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;

class UAWorker extends Worker {

    public function __construct() {

        /*
          @todo: test effective message size
          @todo: message persistance
          @todo: unit tests
          @todo: test uap pushes on a phone
          @todo: UA push code
          @todo: supervisor
         */

        $credentials = array(
            'host' => 'localhost',
            'vhost' => '/',
            'port' => 5672,
            'login' => 'guest',
            'password' => 'guest'
        );

        
        parent::__construct('uap_queue', 'urbanairship_exchange', $credentials);

        $message = $this->getMessage();
        
        if ($message == false) {
            printf("no messages in queue");
            return;
        }


    
       $uaMessage = AMQPUAMessage::unserialize($message->getBody());
 
       var_dump($uaMessage);
       
       
        $this->disconnect();
    }

}

new UAWorker();








