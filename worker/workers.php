#!/usr/bin/env php
<?php
require 'vendor/autoload.php';
require_once('lib/Resque.php');
require_once("config.php");

$jobPath = __DIR__ . '/jobs/';

echo $jobPath;


$handle = @opendir($jobPath);

if ($handle == false)
    die("Could not read $jobPath \n");


// load all classes present under the job directory
while (false !== ($file = readdir($handle))) {
    if ($file == '.' || $file == '..')
        continue;

    if (substr($file, -3) != 'php')
        continue;

    require_once($jobPath . '/' . $file);
}


Resque::setBackend($redisHost . ':' . $redisPort, null);

Resque::redis()->auth($redisPass);

Resque::redis()->select($redisDb);



if ($count > 1) {
    for ($i = 0; $i < $count; ++$i) {
        $pid = Resque::fork();
        if ($pid == -1) {
            die("Could not fork worker " . $i . "\n");
        }
        // Child, start the worker
        else if (!$pid) {
            $queues = explode(',', $queue);
            $worker = new Resque_Worker($queues);
            $worker->logLevel = $logLevel;
            fwrite(STDOUT, '*** Starting worker ' . $worker . "\n");
            $worker->work($interval);
            break;
        }
    }
}
// Start a single worker
else {
    $queues = explode(',', $queue);
    $worker = new Resque_Worker($queues);
    $worker->logLevel = $logLevel;

    $PIDFILE = getenv('PIDFILE');
    if ($PIDFILE) {
        file_put_contents($PIDFILE, getmypid()) or
                die('Could not write PID information to ' . $PIDFILE);
    }

    fwrite(STDOUT, '*** Starting worker ' . $worker . "\n");
    $worker->work($interval);
}
