<?php



// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yiilite.php';
require_once(dirname(__FILE__).'/protected/config/config.php');
$config=dirname(__FILE__).'/protected/client/config/main.php';


require ( 'protected/helpers/globals.php' ) ;

require_once($yii);

$yii =Yii::createWebApplication($config);

//$yii->cache->flush();


$yii->run();