<?php


$yii=dirname(__FILE__).'/../framework/yiilite.php';
require_once(dirname(__FILE__).'/protected/config/config.php');
$config=dirname(__FILE__).'/protected/frontend/config/main.php';
require ( 'protected/helpers/globals.php' ) ;
require_once($yii);
Yii::createWebApplication($config)->run();