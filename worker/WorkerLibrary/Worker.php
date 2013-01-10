<?php

namespace WorkerLibrary;
/**
 * Abstract AMQP Worker (direct exchange)
 * Queues are persistant and manually acknowledged
 *
 * @author jonas
 */
abstract class Worker {

    private $connection;
    private $channel;
    private $exchange;
    private $queue;

    public function __construct($queueName, $exchangeName, $credentials = array()) {

        // create a new connection
        try {
            $this->connection = new \AMQPConnection($credentials);

            $this->connection->connect();
        } catch (AMQPException $e) {
            echo $e->getMessage();
            error_log('connection error: ' . $e->getMessage());
            die;
        }

        // get a channel
        $this->channel = new \AMQPChannel($this->connection);


        // declare a queue
        try {
            $this->queue = new \AMQPQueue($this->channel);

            $this->queue->setName($queueName);
            
            $this->queue->setFlags(AMQP_DURABLE);

            $this->queue->declare();
        } catch (AMQPQueueException $e) {
            error_log('Queue creation error: ' . $e->getMessage());
            die();
        } catch (AMQPConnectionException $e) {
            error_log('Queue creation error: ' . $e->getMessage());
            die();
        }

        // declare an exchange
        try {
            $this->exchange = new \AMQPExchange($this->channel);

            $this->exchange->setName($exchangeName);

            $this->exchange->setType(AMQP_EX_TYPE_DIRECT);

            $this->exchange->declare();
        } catch (AMQPExchangeException $e) {
            error_log('Exchange creation error: ' . $e->getMessage());
            die();
        } catch (AMQPConnectionException $e) {
            error_log('Exchange creation error: ' . $e->getMessage());
            die();
        }

        // bind the queue to the exchange
        try {
            $this->queue->bind($exchangeName, $queueName);
        } catch (AMQPChannelException $e) {
            error_log('Queue binding error:' . $e->getMessage());
            die();
        } catch (AMQPConnectionException $e) {
            error_log('Queue binding error:' . $e->getMessage());
            die();
        }
    }

    protected function getMessage() {
        return $this->queue->get(AMQP_NOPARAM);
    }
    
    protected function acknowledge($deliveryTag){
        $this->queue->ack($deliveryTag);
    }

    protected function disconnect() {
        return $this->connection->disconnect();
    }
    
}