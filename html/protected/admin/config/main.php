<?php

/* BACKEND CONFIG FILE */

$backend = dirname(dirname(__FILE__));
$frontend = dirname($backend);
$site_url = 'http://www.voterguide.com';

Yii::setPathOfAlias('admin', $backend);

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
    'controllerPath' => $backend . '/controllers',
    'viewPath' => $backend . '/views/',
    'runtimePath' => $backend . '/runtime',
    'name' => 'Admin Dashboard - HSLF Mobile ',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'admin.models.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'admin.components.*',
        'application.models.*',
        'application.components.*',
        'ext.multimodelform.MultiModelForm',
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
                'admin' => 'site/index',
                'admin/<_c>' => '<_c>',
                'admin/<_c>/<_a>' => '<_c>/<_a>',
            ),
        ),
        'db' => array(
            'connectionString' => "pgsql:host=$dbhost;port=$dbport;dbname=voterguide",
            'emulatePrepare' => true,
            'username' => $dbuser,
            'password' => $dbpass,
            'charset' => 'UTF-8',
            'schemaCachingDuration' => '3600',
        ),
        'cache' => array(
            'class' => 'system.caching.CApcCache',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning, info, trace',
                ),
                /*
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters' => array('127.0.0.1'),
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'enabled' => YII_DEBUG_SHOW_PROFILER,
                    'categories' => 'system.db.*',
                ),
            // uncomment the following to show log messages on web pages
                
*/
            /* array(
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
        'site_url' => $site_url,
        'upload_path' => $site_url . '/content/upload',
        'upload_dir' => '/../content/upload',
        'share_url' => $site_url // in case we want to store the shared urls in another server
    ),
    'theme' => 'hslf'
);