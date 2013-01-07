<?php

function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/../WorkerLibrary/'.end($parts) . '.php';
}

use WorkerLibrary\AMQPUAMessage as AMQPUAMessage;

$testMessage = new AMQPUAMessage();
$testMessage->apids = array(1, 2, 3, 4);
$testMessage->tokens = array(4,6,7);
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
	printf("could not publish :( ");
}
else{
	printf("message published");
}
 
// close the connection to the amqp broker
$cnn->disconnect();
