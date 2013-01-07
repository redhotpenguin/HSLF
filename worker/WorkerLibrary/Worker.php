<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * AMQP Worker (direct exchange)
 *
 * @author jonas
 */
abstract class Worker {

    private $connection;
    private $channel;
    private $exchange;
    protected $queue;

    public function __construct($queueName, $exchangeName, $options = array()) {


        // create a new connection
        try {
            $this->connection = new AMQPConnection();
            $this->connection->connect();
        } catch (AMQPException $e) {
            error_log('connection error: ' . $e->getMessage());
            die;
        }

        // get a channel
        $this->channel = new AMQPChannel($this->connection);


        // declare a queue
        try {
            $this->queue = new AMQPQueue($this->channel);

            $this->queue->setName($queueName);

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
            $this->exchange = new AMQPExchange($this->channel);

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

}

?>
