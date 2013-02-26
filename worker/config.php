<?php

$dotcloud_conf = "/home/dotcloud/environment.json";

if (file_exists($dotcloud_conf)) {     // dotcloud server conf
    $env = json_decode(file_get_contents($dotcloud_conf), true);
    $redisHost = $env['DOTCLOUD_SHM_SSH_HOST'];
    $redisPort = $env['DOTCLOUD_SHM_REDIS_PORT'];
    $redisPassword = $env['DOTCLOUD_SHM_REDIS_PASSWORD'];
    $redisDb = 1;
} else {
    $redisHost = '127.0.0.1';
    $redisPort = 6379;
    $redisPassword = 'foobared';
    $redisDb = 2;
}


$jobPath = __DIR__ . '/jobs';
$queue = 'mobile_platform';
$interval = 1;
$count = 1;
$logLevel = Resque_Worker::LOG_NONE; // or LOG_VERBOSE
//
//worker specific
define('JOB_PATH', $jobPath);
define('WORKER_QUEUE', $queue);
define('WORKER_INTERVAL', $interval);
define('WORKER_COUNT', $count);
define('WORKER_LOG_LEVEL', $logLevel);

// redis queue specific
define('REDIS_HOST', $redisHost);
define('REDIS_PORT', $redisPort);
define('REDIS_PASSWORD', $redisPassword);
define('REDIS_DB', $redisDb);

