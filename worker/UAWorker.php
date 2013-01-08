<?php

include_once("bootstrap.php");

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;
use WorkerLibrary\UrbanAirship as messenger;

class UAWorker extends Worker {

    public function __construct() {

        $credentials = array(
            'host' => RABBITMQ_HOST,
            'vhost' => RABBITMQ_VHOST,
            'port' => RABBITMQ_PORT,
            'login' =>  RABBITMQ_LOGIN,
            'password' =>  RABBITMQ_PASSWORD
        );


        parent::__construct('uap_queue', 'urbanairship_exchange', $credentials);

        $message = $this->getMessage();

        if ($message == false) {
            printf("no messages in queue\n");
            return;
        }


        $uaMessage = AMQPUAMessage::unserialize($message->getBody());

        printf("got a message from %s\n", $uaMessage->clientName);
        printf("sending %s \n", $uaMessage->alert);
        
        $messenger = new messenger($uaMessage->apiKey, $uaMessage->apiSecret);
        
        $result = $messenger->sendPushNotification($uaMessage->alert, $uaMessage->tokens, $uaMessage->apids, $uaMessage->extra);
        
        printf (  "push result: %s \n", $result);
        
        $this->acknowledge( $message->getDeliveryTag() );

        $this->disconnect();
    }

}

new UAWorker();








