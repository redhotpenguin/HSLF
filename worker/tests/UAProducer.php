<?php

function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/../WorkerLibrary/'.end($parts) . '.php';
}

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;

$testMessage = new AMQPUAMessage();

$apids = array();

$tokens = array();

// generate fake apids
for($i=0; $i< 1000; $i++){
    array_push($apids, md5(microtime()));
}

// generate fake tokens
for($i=0; $i< 1000; $i++){
    array_push($tokens, md5(microtime()));
}

$testMessage->apids = $apids;
$testMessage->tokens = $tokens;
$testMessage->customData = array("foo"=>"bar", "open"=>"screen4");

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
 
$queue->declare();


// get an exchange
$exchange = new AMQPExchange( $channel );
 
$exchange->setName( $exchangeName );

$exchange->setType( AMQP_EX_TYPE_DIRECT );
 
$exchange->declare();
 
// bind our queue to the exchange using the routing key
// direct exchange: routing key == queue name
$queue->bind( $exchangeName , $queueName );
 

// Publish our message!
$ep = $exchange->publish( $message,  $queueName );
 
if(!$ep){
	printf("could not publish :(\n ");
}
else{
	printf("message published\n");
}
 
// close the connection to the amqp broker
$cnn->disconnect();
