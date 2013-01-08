<?php

$opt = getopt("m:");


if (isset($opt['m']))
    $msg = $opt['m'];
else
    $msg = "hello world";

function __autoload($class) {
    $parts = explode('\\', $class);
    require __DIR__ . '/../WorkerLibrary/' . end($parts) . '.php';
}

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;


$tokens = array();
$apids = array(
    '5d1cf0cb-90ce-4960-8c12-adc8c8bec35e'
);
$extra = array("foo" => "bar", "open" => "screen4");



$clientInfo = new \WorkerLibrary\ClientInfo("Jonas", "jonas@winningmark.com", "G3QQPQEERdKchSqDPq6Gag", "FT98LRhLRNOPHBg8k-5iyg");

$payload = new \WorkerLibrary\Payload($msg, $tokens, $apids, $extra);




$testMessage = new AMQPUAMessage($clientInfo, $payload);


$message = $testMessage->serialize();


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

if (!$ep) {
    printf("could not publish :(\n ");
} else {
    printf("message published\n");
}

// close the connection to the amqp broker
$cnn->disconnect();
