<?php

include_once("bootstrap.php");

use WorkerLibrary\Worker as Worker;
use WorkerLibrary\UrbanAirship as messenger;

class UAWorker extends Worker {

    private $credentials = array(
        'host' => RABBITMQ_HOST,
        'vhost' => RABBITMQ_VHOST,
        'port' => RABBITMQ_PORT,
        'login' => RABBITMQ_LOGIN,
        'password' => RABBITMQ_PASSWORD
    );
    private $mongoCredentials = array(
        'db' => MONGODB_NAME,
        'password' => MONGODB_PASSWORD,
        'username' => MONGODB_USERNAME,
        'timeout' => MONGODB_CONNECT_TIMEOUT
    );
    private $mongoClient;
    private $db;
    private $collection;

    public function __construct() {

        parent::__construct('uap_queue', 'urbanairship_exchange', $this->credentials);

        $this->connect();

        $this->mongoDBConnect();
    }

    public function __destruct() {
        // disconnect from the broker
        $this->disconnect();

        $this->mongoClient->close();
    }

    public function processJob() {
        if (!$this->isHealthy())
            return false;

        $message = $this->getMessage();

        if ($message == false) {
            return true;
        }

        $uaMessage = AMQPUAMessage::unserialize($message->getBody());

        // printf("Got a message from: %s\n", $uaMessage->getClientInfo()->getName());
        //  printf("Sending: %s \n", $uaMessage->getPayload()->getAlert());

        $messenger = new messenger($uaMessage->getClientInfo()->getApiKey(), $uaMessage->getClientInfo()->getApiSecret());


        $searchAttributes = $uaMessage->getPayload()->getSearchAttributes();


        $cursor = $this->collection->find($searchAttributes);

        $apids = array();
        $tokens = array();


        foreach ($cursor as $user) {

            if ($user['device_type'] === 'android') {
                array_push($apids, $user['ua_identifier']);
            } elseif ($user['device_type'] === 'ios') {
                array_push($tokens, $user['ua_identifier']);
            }
        }

        if (empty($apids) && empty($tokens)) {
            $this->acknowledge($message->getDeliveryTag());
            return true;
        }


        printf("Sending to %d android users \n", count($apids));
        printf("Sending to %d ios users \n", count($tokens));


        $result = $messenger->sendPushNotification($uaMessage->getPayload()->getAlert(), $tokens, $apids, $uaMessage->getPayload()->getExtra());

        $result = ($result ? 'success' : 'failure');

        printf("Push result: %s \n", $result);

        $this->acknowledge($message->getDeliveryTag());

        return true;
    }

    private function mongoDBConnect() {
        try {
            $this->mongoClient = new MongoClient(MONGODB_HOST, $this->mongoCredentials); // connect
        } catch (MongoConnectionException $e) {
            printf("Could not connect to mongodb database: %s\n ", $e->getMessage());
            return;
        }

        $this->db = $this->mongoClient->selectDB(MONGODB_NAME);

        try {
            $this->collection = new MongoCollection($this->db, MONGODB_COLLECTION_NAME); // @todo: exception handling
        } catch (Exception $e) {
            printf("Could not get a collection: %s\n ", $e->getMessage());
            return;
        }
    }

    public function isHealthy() {
        if ($this->mongoClient == null)
            return false;

        return ($this->mongoClient->connected === true && $this->connection->isConnected() === true );
    }

}

//$worker = new UAWorker();
//$worker->processJob();