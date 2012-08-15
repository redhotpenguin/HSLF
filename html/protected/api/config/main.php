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
    // preloading 'log' component
    // 'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'admin.models.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'admin.components.*',
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
    /*
      'gii' => array(
      'class' => 'system.gii.GiiModule',
      'password' => 'giipass',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters' => array('127.0.0.1', '::1'),
      ),
     */
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
                array('api/list', 'pattern' => 'api/<model:\w+>', 'verb' => 'GET'),
                //   ex: /api/ballot_items/state/or/?districts=...
                array('api/view', 'pattern' => 'api/<model:\w+>/state/<state_abbr:\w{2,3}>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/state/<state_abbr:\w{2,3}>/district/<district_number:\d+>', 'verb' => 'GET'),
                // ex: /api/ballot_items/2012/state/or/?districts=...
                array('api/view', 'pattern' => 'api/<model:\w+>/<year:\d{4}>/state/<state_abbr:\w{2,3}>', 'verb' => 'GET'),
                // ex: /api/ballot_items/single/4141/
                array('api/view', 'pattern' => 'api/<model:\w+>/single/<ballot_item_id:\d+>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/name/<type:\w+>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>/', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/types', 'verb' => 'GET'), // ex: /api/districts/type
                array('api/view', 'pattern' => 'api/<model:\w+>/type/<type:\w+>', 'verb' => 'GET'),
                array('api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'),
                array('api/update', 'pattern' => 'api/<model:\w+>/device_token/<device_token:\w+>/<action:\w+>', 'verb' => 'POST'),
                // ex: /api/ballot_items/search/suzane bonamici/
                array('api/search', 'pattern' => 'api/<model:\w+>/search/<query>', 'verb' => 'GET'),
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
            // use 'api/error' action to display errors
            'errorAction' => 'api/index',
        ),
    /*
      'log' => array(
      'class' => 'CLogRouter',
      'routes' => array(
      array(
      'class' => 'CFileLogRoute',
      'levels' => 'error, warning, info, trace',
      ),
      // uncomment the following to show log messages on web pages

      array(
      'class'=>'CWebLogRoute',
      ),
      ),
      ), */
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
        'share_url' => SITE_URL, // in case we want to store the shared urls in another server
    ),
);