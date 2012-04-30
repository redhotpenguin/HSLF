<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Winning Mark Mobile',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
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
                array('api/create', 'pattern' => 'api/<model:\w+>', 'verb' => 'POST'),
                // Other controllers
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
        ),
        // uncomment the following to use a PostSQL database
        $dbhost = $env['DOTCLOUD_DB_SQL_HOST'] || 'localhost';
        $dbuser = $env['DOTCLOUD_DB_SQL_LOGIN'] || 'root';
        $dbpass = $env['DOTCLOUD_DB_SQL_PASSWORD'] || 'pengu1n';
        $dbport = $env['DOTCLOUD_DB_SQL_PORT'] || '5431';

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
           'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				/*array(
					'class'=>'CWebLogRoute',
				),*/
				
			),
		),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'adminEmail' => 'jonas@winningmark.com',
        'api_username' => 'secretuser',
        'api_password' => 'secretpassword',
        'api_salt' => '1qV2453L674133', //never changes this value once in production!
        'urbanairship_app_key' => 'ouRCLPaBRRasv4K1AIw-xA',
        'urbanairship_app_master_secret' => '7hd19C6rSzyrbKM3k6KqDg',
    ),
    'theme' => 'hslf'
);