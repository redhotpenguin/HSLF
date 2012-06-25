<?php

// change the following paths if necessary
 session_start();
 ob_start();
$yiit=dirname(__FILE__).'/../../../../framework/yiit.php';

include_once(dirname(__FILE__).'/../config/config.php');
$yiit = 'C:/xampp/htdocs/HSLF/framework/yiit.php';


$config=dirname(__FILE__).'/../config/test.php';

$config = 'C:/xampp/htdocs/HSLF/html/protected/api/config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

Yii::createWebApplication($config);
