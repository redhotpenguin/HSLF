<?php


require_once(dirname(__FILE__).'/html/protected/config/config.php');

// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/html/protected/config/console.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require ( 'html/protected/helpers/globals.php' ) ;



require_once($yii);

$app = Yii::createConsoleApplication($config);

$app->run();