<?php

/* API specific config: */

$api = dirname(dirname(__FILE__));
$frontend = dirname($api);
Yii::setPathOfAlias('admin', $api);

if (isset($env['DOTCLOUD_DB_SQL_HOST']))
    $dbhost = $env['DOTCLOUD_DB_SQL_HOST'];
else
    $dbhost = 'localhost';

if (isset($env['DOTCLOUD_DB_SQL_LOGIN']))
    $dbuser = $env['DOTCLOUD_DB_SQL_LOGIN'];
else
    $dbuser = 'postgres';


if (isset($env['DOTCLOUD_DB_SQL_PASSWORD']))
    $dbpass = $env['DOTCLOUD_DB_SQL_PASSWORD'];
else
    $dbpass = 'pengu1n';



if (isset($env['DOTCLOUD_DB_SQL_PORT']))
    $dbport = $env['DOTCLOUD_DB_SQL_PORT'];
else
    $dbport = '5432';


return array(
    //'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'basePath' => $frontend,
    'controllerPath' => $api . '/controllers',
    'viewPath' => $api . '/views/',
    'runtimePath' => $api . '/runtime',
    'name' => 'API - HSLF Mobile ',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'admin.models.*',
        'application.shared.models.*',
        'admin.components.*',
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'giipass',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
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
                array('api/view', 'pattern' => 'api/<model:\w+>/state/<state_abbr:\w{2,3}>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/state/<state_abbr:\w{2,3}>/district/<district_number:\d+>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/name/<type:\w+>', 'verb' => 'GET'),
                array('api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>/', 'verb' => 'GET'), // ex: /api/candidate/12/
                array('api/view', 'pattern' => 'api/<model:\w+>/<id:\d+>/<filter:\w+>', 'verb' => 'GET'), // ex: /api/candidate/12/issue
                array('api/view', 'pattern' => 'api/<model:\w+>/type/<type:\w+>', 'verb' => 'GET'),
                array('api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'),
                array('api/update', 'pattern' => 'api/<model:\w+>/device_token/<device_token:\w+>/<action:\w+>', 'verb' => 'POST'),
            ),
        ),
        'db' => array(
            'connectionString' => "pgsql:host=$dbhost;port=$dbport;dbname=voterguide",
            'emulatePrepare' => true,
            'username' => $dbuser,
            'password' => $dbpass,
            'charset' => 'UTF-8',
            'schemaCachingDuration' => '600',
        ),
        /*
          'cache' => array(
          'class' => 'system.caching.CApcCache',
          ),
         */
        'errorHandler' => array(
            // use 'api/error' action to display errors
            'errorAction' => 'api/index',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info, trace',
                ),
            // uncomment the following to show log messages on web pages

           /*  array(
              'class'=>'CWebLogRoute',
              ), */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'adminEmail' => 'jonas@winningmark.com',
        'api_key' => 'w-TCispEQW-MLev82TVyO_X',
        'api_secret' => 'PqiW_IDKL3mFi_OirCqOe-u',
        'urbanairship_app_key' => 'ouRCLPaBRRasv4K1AIw-xA',
        'urbanairship_app_master_secret' => '7hd19C6rSzyrbKM3k6KqDg',
        'site_url' => 'http://www.voterguide.com/',
    ),
);