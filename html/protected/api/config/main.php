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
    'name' => PROJECT_NAME . ' - API',
    'preload' => array('log'),
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
                array('api/list', 'pattern' => 'api/<tennant_id:\d+>/<model:\w+>/', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>', 'verb' => 'GET'),

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
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CWebLogRoute',
                    'levels' => 'trace,info,error,warning',
                    'categories' => 'system.db.CDbCommand',
                    'filter' => array(
                        'class' => 'CLogFilter',
                        'prefixSession' => true,
                        'prefixUser' => false,
                        'logUser' => false,
                        'logVars' => array(),
                    ),
            ))),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'adminEmail' => ADMIN_EMAIL,
        'api_key' => API_KEY,
        'api_secret' => API_SECRET,
        'urbanairship_app_key' => UA_API_KEY,
        'urbanairship_app_master_secret' => UA_API_SECRET,
        'site_url' => SITE_URL,
        'share_url' => SHARE_URL, // in case we want to store the shared urls in another server
    ),
);