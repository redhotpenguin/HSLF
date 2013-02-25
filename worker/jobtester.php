<?php


require 'vendor/autoload.php';

require_once('config.php');


Resque::setBackend(REDIS_HOST . ':' . REDIS_PORT, null);

Resque::redis()->auth(REDIS_PASSWORD);

Resque::redis()->select(REDIS_DB);

$parameters = array('Foo'=>'BAR');

Resque::enqueue('mobile_platform', 'TestJob', $parameters);