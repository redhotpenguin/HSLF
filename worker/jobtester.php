<?php


require 'vendor/autoload.php';

Resque::setBackend("127.0.0.1:6379", null);

$connected = Resque::redis()->auth('foobared');

Resque::redis()->select(2);

$parameters = array('Foo'=>'BAR');

Resque::enqueue('mobile_platform', 'TestJob', $parameters);