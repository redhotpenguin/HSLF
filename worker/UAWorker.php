<?php

include_once("bootstrap.php");

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;


class UAWorker extends Worker {

    public function __construct() {

        /*
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

        printf("got a message from %s", $uaMessage->clientName);
        
        $this->acknowledge( $message->getDeliveryTag() );

        $this->disconnect();
    }

}

new UAWorker();








