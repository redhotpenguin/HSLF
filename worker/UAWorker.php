<?php

include_once("bootstrap.php");

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;

class UAWorker extends Worker {

    public function __construct() {

        /*
          @todo: abstract UAWorker->Worker
          @todo: test effective message size
          @todo: message persistance
          @todo: unit tests
         */

        $queueName = 'uap_queue';
        $exchangeName = 'urbanairship_exchange';

        parent::__construct($queueName, $exchangeName);
        
  

        $message = $this->queue->get(AMQP_AUTOACK);

        if ($message == false) {
            printf("no messages in queue $queueName");
            return;
        }

        $uaMessage = AMQPUAMessage::unserialize($message->getBody());
        var_dump($uaMessage);

        //  echo $message->getBody(); // print Hello World!	


        $cnn->disconnect();
    }

}

new UAWorker();








