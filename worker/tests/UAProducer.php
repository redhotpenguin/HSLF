<?php

function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/../WorkerLibrary/'.end($parts) . '.php';
}

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;

$testMessage = new AMQPUAMessage();

$apids = array(
    '5d1cf0cb-90ce-4960-8c12-adc8c8bec35e'
);

$tokens = array();


$testMessage->clientName = "jonas palmero";
$testMessage->clientEmail = "jonas@winningmark.com";
$testMessage->apiKey = "G3QQPQEERdKchSqDPq6Gag";
$testMessage->apiSecret = "FT98LRhLRNOPHBg8k-5iyg";
$testMessage->apids = $apids;
$testMessage->tokens = $tokens;
$testMessage->extra = array("foo"=>"bar", "open"=>"screen4");
$testMessage->alert = "hello RabbitMQ";

$message = $testMessage->serialize();


$queueName = 'uap_queue';
$exchangeName = 'urbanairship_exchange';

// Create a new connection
$cnn = new AMQPConnection();
 
$cnn->connect();
 
// create a new channel, based on our existing connection
$channel = new AMQPChannel( $cnn );
 
// get a queue object

$queue = new AMQPQueue( $channel );

/*
The queue name must be set before we call declare(). 
Otherwise, a random queue name will be generated
*/
$queue->setName( $queueName );
 
$queue->setFlags(AMQP_DURABLE);

$queue->declare();


// get an exchange
$exchange = new AMQPExchange( $channel );
 
$exchange->setName( $exchangeName );

$exchange->setType( AMQP_EX_TYPE_DIRECT );
 
$exchange->declare();
 
// bind our queue to the exchange using the routing key
// direct exchange: routing key == queue name
$queue->bind( $exchangeName , $queueName );
 

// Publish our persistant message!
$ep = $exchange->publish( $message, $queueName, AMQP_NOPARAM, array('delivery_mode' => 2) );
 
if(!$ep){
	printf("could not publish :(\n ");
}
else{
	printf("message published\n");
}
 
// close the connection to the amqp broker
$cnn->disconnect();
