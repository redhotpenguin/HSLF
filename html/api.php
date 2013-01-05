<?php



// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yiilite.php';
require_once(dirname(__FILE__).'/protected/config/config.php');

$config=dirname(__FILE__).'/protected/api/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true  );
// specify how many levels of call stack should be shown in each log message
//defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
//defined('YII_DEBUG_SHOW_PROFILER') or define('YII_DEBUG_SHOW_PROFILER', true);

require_once($yii);

require ( 'protected/helpers/globals.php' ) ;

$yii =Yii::createWebApplication($config);


//$yii->cache->flush();

$yii->run();
