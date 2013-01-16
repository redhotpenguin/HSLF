<?php



// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yiilite.php';
require_once(dirname(__FILE__).'/protected/config/config.php');

$config=dirname(__FILE__).'/protected/api/config/main.php';

require_once($yii);

require ( 'protected/helpers/globals.php' ) ;

$yii =Yii::createWebApplication($config);


//$yii->cache->flush();

$yii->run();
