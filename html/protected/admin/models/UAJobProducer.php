<?php

/**
 * @todo: unit tests
 * @todo: refactor this class
 * JobProducer
 * Add jobs to a RabbitMQ queue
 * @author jonas
 */
class UAJobProducer {

    private $tenant;

    public function __construct(Tenant $tenant) {
        if (!extension_loaded('amqp'))
            throw new JobProducerException(JobProducerException::AMQP_EXTENSION_ERROR);
        $this->tenant = $tenant;
    }

    public function pushUrbanAirshipMessage($alert, $searchAttributes, $extra) {

        $payload = new Payload($alert, $searchAttributes, $extra);
        
        $searchAttributes['tenant_id'] = $this->tenant->id;

        $clientInfo = new ClientInfo($this->tenant->name, $this->tenant->email, $this->tenant->ua_api_key, $this->tenant->ua_api_secret);

        $messageObject = new AMQPUAMessage($clientInfo, $payload);

        $message = $messageObject->serialize();


        $queueName = 'uap_queue';
        $exchangeName = 'urbanairship_exchange';



        $credentials = array(
            'host' => Yii::app()->params['rabbitMQHost'],
            'port' => Yii::app()->params['rabbitMQPort'],
            'vhost' => Yii::app()->params['RABBITMQ_VHOST'],
            'login' => Yii::app()->params['rabbitMQLogin'],
            'password' => Yii::app()->params['rabbitMQPassword'],
        );

        try {
            $cnn = new AMQPConnection($credentials);
            $cnn->connect();
            $channel = new AMQPChannel($cnn);
        } catch (Exception $e) {
            throw new JobProducerException(JobProducerException::CONNECTION_ERROR);
        }

        try {
            $queue = new AMQPQueue($channel);

            $queue->setName($queueName);

            $queue->setFlags(AMQP_DURABLE);

            $queue->declare();
        } catch (AMQPChannelException $e) {
            throw new JobProducerException(JobProducerException::QUEUE_ERROR);
        } catch (AMQPConnectionException $e) {
            throw new JobProducerException(JobProducerException::CONNECTION_ERROR);
        }

        try {
            // get an exchange
            $exchange = new AMQPExchange($channel);

            $exchange->setName($exchangeName);

            $exchange->setType(AMQP_EX_TYPE_DIRECT);

            $exchange->declare();
        } catch (AMQPExchangeException $e) {
            throw new JobProducerException(JobProducerException::EXCHANGE_ERROR);
        } catch (AMQPConnectionException $e) {
            throw new JobProducerException(JobProducerException::CONNECTION_ERROR);
        }

        try {
            // bind our queue to the exchange using the routing key
            // direct exchange: routing key == queue name
            $bindResult = $queue->bind($exchangeName, $queueName);

            if ($bindResult == false) {
                throw new JobProducerException(JobProducerException::BIND_ERROR);
            }
        } catch (AMQPChannelException $e) {
            throw new JobProducerException(JobProducerException::BIND_ERROR);
        } catch (AMQPConnectionException $e) {
            throw new JobProducerException(JobProducerException::CONNECTION_ERROR);
        }

        // Publish our persistant message!
        $ep = $exchange->publish($message, $queueName, AMQP_NOPARAM, array('delivery_mode' => 2));


        // close the connection to the amqp broker
        $cnn->disconnect();


        return $ep;
    }

}

