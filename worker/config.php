<?php

// worker configuration
$queue = 'mobile_platform';
$redisHost = '127.0.0.1';
$redisPort = 6379;
$redisPass = 'foobared';
$redisDb = 2;
$logLevel = Resque_Worker::LOG_NONE; // or LOG_VERBOSE
$jobPath = __DIR__ . '/jobs';
$interval = 1;
$count = 1;