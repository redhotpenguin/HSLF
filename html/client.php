<?php

// change the following paths if necessary
$yii = dirname(__FILE__) . '/../framework/yiilite.php';
require_once(dirname(__FILE__) . '/protected/config/config.php');
$config = dirname(__FILE__) . '/protected/client/config/main.php';

require ( 'protected/helpers/globals.php' );

require_once($yii);
try {
    $yii = Yii::createWebApplication($config);

    $yii->run();
} catch (Exception $e) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 60');
    echo '<h1>Service unavailable</h1>';
}