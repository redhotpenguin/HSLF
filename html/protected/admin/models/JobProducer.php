<?php

/**
 * @todo: unit tests
 * @todo: refactor this class
 * JobProducer
 * Add jobs to a RabbitMQ queue
 * @author jonas
 */
class JobProducer {

    private $tenant;

    public function __construct(Tenant $tenant) {
        $this->tenant = $tenant;
    }

    public function pushUrbanAirshipMessage($alert, $searchAttributes, $extra) {

        $payload = new Payload($alert, $searchAttributes, $extra);

        $clientInfo = new ClientInfo($this->tenant->name, $this->tenant->email, $this->tenant->ua_api_key, $this->tenant->ua_api_secret);
        
        $messageObject = new AMQPUAMessage($clientInfo, $payload);

        $message = $messageObject->serialize();


        $queueName = 'uap_queue';
        $exchangeName = 'urbanairship_exchange';

// Create a new connection
        $cnn = new AMQPConnection();

        $cnn->connect();

// create a new channel, based on our existing connection
        $channel = new AMQPChannel($cnn);

        // get a queue object

        $queue = new AMQPQueue($channel);

        /*
          The queue name must be set before we call declare().
          Otherwise, a random queue name will be generated
         */
        $queue->setName($queueName);

        $queue->setFlags(AMQP_DURABLE);

        $queue->declare();


        // get an exchange
        $exchange = new AMQPExchange($channel);

        $exchange->setName($exchangeName);

        $exchange->setType(AMQP_EX_TYPE_DIRECT);

        $exchange->declare();

        // bind our queue to the exchange using the routing key
        // direct exchange: routing key == queue name
        $queue->bind($exchangeName, $queueName);


        // Publish our persistant message!
        $ep = $exchange->publish($message, $queueName, AMQP_NOPARAM, array('delivery_mode' => 2));


        // close the connection to the amqp broker
        $cnn->disconnect();


        return $ep;
    }

}

