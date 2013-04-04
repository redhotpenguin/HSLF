<?php

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yiilite.php';
require_once(dirname(__FILE__) . '/protected/config/config.php');
$config = dirname(__FILE__) . '/protected/client/config/main.php';

require ( 'protected/helpers/globals.php' );

require_once($yii);

$yii = Yii::createWebApplication($config);

 
try {   
    $yii->run();
} catch (CDbException $e) {
    if ($e->getCode() == 7) {
        header('HTTP/1.1 503 Service Temporarily Unavailable');
        header('Status: 503 Service Temporarily Unavailable');
        header('Retry-After: 60');
        echo '<h1>Service unavailable</h1> Database Connection Lost';
        die;
    }
    throw $e;
}