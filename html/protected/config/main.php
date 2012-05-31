<?php


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
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'HSLF Mobile',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.shared.models.*',
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
            // uncomment the following to show log messages on web pages

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
        'site_url' => 'http://www.voterguide.com/',
    ),
    'theme' => 'hslf_frontend'
);