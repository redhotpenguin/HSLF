<?php

/*
 * usage:
 * php html/protected/yiic.php shell /var/www/html/mobile_platform/html/protected/config/console.php
 */

// change the following paths if necessary

require_once(dirname(__FILE__).'/config/config.php');

$yiic=dirname(__FILE__).'/../../framework/yiic.php';


$config=dirname(__FILE__).'/commands/config/main.php';

require_once($yiic);
