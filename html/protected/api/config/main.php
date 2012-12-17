<?php

// API CONFIG FILE

$api = dirname(dirname(__FILE__));
$frontend = dirname($api);
Yii::setPathOfAlias('admin', $api);

return array(
    //'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'basePath' => $frontend,
    'controllerPath' => $api . '/controllers',
    'viewPath' => $api . '/views/',
    'runtimePath' => $api . '/runtime',
    'name' => 'MVG - API',
    // 'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.api.models.rest.criteria.*',
        'application.api.models.rest.*',
        'application.api.models.geocodingclient.*',
        'admin.models.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'admin.components.*',
        'application.models.*',
        'application.components.*',
        'ext.directmongosuite.components.*',
    ),
    'modules' => array(
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                array('api/index', 'pattern' => 'api', 'verb' => 'GET'),
                array('api/list', 'pattern' => 'api/<tenant_id:\d+>/<model:\w+>/', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<tenant_id:\d+>/<model:\w+>/<id:\d+>', 'verb' => 'GET'),
                array('api/create', 'pattern' => 'api/<tenant_id:\d+>/<model:\w+>/', 'verb' => 'POST'),
                array('api/update', 'pattern' => 'api/<tenant_id:\d+>/<model:\w+>/<id:\w+>', 'verb' => 'PUT'),
            ),
        ),
        'db' => array(
            'connectionString' => "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'UTF-8',
            'schemaCachingDuration' => '3600',
        ),
        'cache' => array(
            'class' => 'system.caching.CApcCache',
        ),
        'errorHandler' => array(
            // use 'api/error' action to display errors
            'errorAction' => 'api/index',
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
    ),
    'behaviors' => array(
        'edms' => array(
            'class' => 'EDMSBehavior'
        )
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s'
    ),
);