<?php

include_once("bootstrap.php");

use WorkerLibrary\Worker as Worker;
use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;
use WorkerLibrary\ClientInfo as ClientInfo;
use WorkerLibrary\Payload as Payload;
use WorkerLibrary\UrbanAirship as messenger;

class UAWorker extends Worker {

    public function __construct() {

        $credentials = array(
            'host' => RABBITMQ_HOST,
            'vhost' => RABBITMQ_VHOST,
            'port' => RABBITMQ_PORT,
            'login' => RABBITMQ_LOGIN,
            'password' => RABBITMQ_PASSWORD
        );

        $mongoCredentials = array(
            'db' => MONGODB_NAME,
            'password' => MONGODB_PASSWORD,
            'username' => MONGODB_USERNAME,
            'timeout' => MONGODB_CONNECT_TIMEOUT
        );

        try {
            $mongoClient = new MongoClient(MONGODB_HOST, $mongoCredentials); // connect
        } catch (MongoConnectionException $e) {
            printf("Could not connect to mongodb database: %s\n ", $e->getMessage());
            printf("Not acknowledging");
            return;
        }

        $db = $mongoClient->selectDB(MONGODB_NAME);
        
        try {
            $collection = new MongoCollection($db, MONGODB_COLLECTION_NAME); // @todo: exception handling
        } catch (Exception $e) {
            printf("Could not get a collection: %s\n ", $e->getMessage());
            printf("Not acknowledging");
            return;
        }

        parent::__construct('uap_queue', 'urbanairship_exchange', $credentials);

        $message = $this->getMessage();

        if ($message == false) {
            printf("no messages in queue\n");
            return;
        }


        $uaMessage = AMQPUAMessage::unserialize($message->getBody());

        printf("Got a message from: %s\n", $uaMessage->getClientInfo()->getName());
        printf("Sending: %s \n", $uaMessage->getPayload()->getAlert());

        $messenger = new messenger($uaMessage->getClientInfo()->getApiKey(), $uaMessage->getClientInfo()->getApiSecret());


        $searchAttributes = $uaMessage->getPayload()->getSearchAttributes();


        $cursor = $collection->find($searchAttributes);

        $apids = array();
        $tokens = array();
        foreach ($cursor as $user) {
            if ($user['device_type'] === 'android') {
                array_push($apids, $user['device_identifier']);
            } elseif ($user['device_type'] === 'ios') {
                array_push($tokens, $user['device_identifier']);
            }
        }

        printf("Sending to %d android users \n", count($apids));
        printf("Sending to %d ios users \n", count($tokens));


        $result = $messenger->sendPushNotification($uaMessage->getPayload()->getAlert(), $tokens, $apids, $uaMessage->getPayload()->getExtra()
        );

        printf("Push result: %s \n", $result);

        $this->acknowledge($message->getDeliveryTag());

        $this->disconnect();
    }

}

new UAWorker();








