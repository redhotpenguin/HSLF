<?php

/* Console config FILE */

$clientDirectory = dirname(dirname(__FILE__));
$appDirectory = dirname($clientDirectory);

Yii::setPathOfAlias('admin', $clientDirectory);

return array(
    'basePath' => $appDirectory,
    'name' => 'WM Mobile - Console',
    'runtimePath' => $clientDirectory . '/runtime',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.shared.components.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
    ),
    'modules' => array(
    ),
    // application components
    'components' => array(
        'db' => array(
            'connectionString' => "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'UTF-8',
        ),
        'authManager' => array(
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
);