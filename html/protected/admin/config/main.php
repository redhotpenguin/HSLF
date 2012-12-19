<?php

/* BACKEND CONFIG FILE */

$backend = dirname(dirname(__FILE__));
$frontend = dirname($backend);
$html_directory = dirname($_SERVER['SCRIPT_FILENAME']);


Yii::setPathOfAlias('admin', $backend);


return array(
//'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'basePath' => $frontend,
    'controllerPath' => $backend . '/controllers',
    'viewPath' => $backend . '/views/',
    'runtimePath' => $backend . '/runtime',
    'name' => 'Admin Dashboard - Winning Mark Mobile ',
    // preloading 'log' component
    'preload' => array(
// 'log',
        'bootstrap'
    ), // preload the bootstrap component),
// autoloading model and component classes
    'import' => array(
        'admin.models.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'admin.components.*',
        'application.models.*',
        'application.components.*',
        'ext.directmongosuite.components.*',
    ),
    'modules' => array(),
    // application components
    'components' => array(
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        // uncomment the following to enable URLs in path-format

        'bootstrap' => array(
            'class' => 'ext.bootstrap.components.Bootstrap', // assuming you extracted bootstrap under extensions
            'responsiveCss' => true,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            // mapping
            'rules' => array(
                'admin' => 'site/index',
                'admin/<_controller>' => '<_controller>',
                'admin/<_controller>/<_action>' => '<_controller>/<_action>',
            ),
        ),
        'db' => array(
            'connectionString' => "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'UTF-8',
        // 'schemaCachingDuration' => '3600',
        ),
        'cache' => array(
            'class' => 'system.caching.CApcCache',
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'edms' => array(
            'class' => 'EDMSConnection',
            'server' => MONGODB_HOST,
            'dbName' => MONGODB_NAME,
            'options' => array(
                'db' => MONGODB_NAME,
                'username' => MONGODB_USER,
                'password' => MONGODB_PASS)
        )
    ,
    ),
    // application-level parameters that can be accessed
// using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'site_url' => SITE_URL,
        'upload_path' => SITE_URL . UPLOAD_PATH,
        'upload_dir' => UPLOAD_DIR,
        'html_directory' => $html_directory,
        'mongodb_ack_level' => MONGODB_ACK_LEVEL
    ),
    'behaviors' => array(
        'edms' => array(
            'class' => 'EDMSBehavior'
        )
    ),
    'theme' => ADMIN_THEME,
);