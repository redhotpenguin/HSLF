<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../../shared/extensions/bootstrap');

/* Frontend CONFIG FILE */

$frontendDirectory = dirname(dirname(__FILE__));
$appDirectory = dirname($frontendDirectory);

// frontend config
return array(
    'basePath' => $appDirectory,
    'controllerPath' => $frontendDirectory . '/controllers',
    'viewPath' => $frontendDirectory . '/views/',
    'runtimePath' => $frontendDirectory . '/runtime',
    'name' => "Winning Mark Mobile",
    // preloading 'log' component
    'preload' => array('bootstrap'),
    // autoloading model and component classes
    'import' => array(
        // shared
        'application.shared.components.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
    ),
    'modules' => array(
    /*
      'gii' => array(
      'class' => 'system.gii.GiiModule',
      'password' => 'giipass',
      'ipFilters' => array('127.0.0.1', '::1'),
      'generatorPaths' => array(
      'bootstrap.gii',
      ),
      ), */
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
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
            'class' => 'application.shared.extensions.Redis.CRedisCache',
            'predisPath' => 'application.shared.extensions.Redis.Predis',
            'servers' => array(
                array(
                    'host' => REDIS_HOST,
                    'port' => REDIS_PORT,
                    'password' => REDIS_PASSWORD,
                    'database' => REDIS_DATABASE
                ),
        )),
        's3' => array('class' => 'application.shared.extensions.S3.ES3',
            'aKey' => S3_AKEY,
            'sKey' => S3_SKEY,
        ),
        'assetManager' => array(
            'class' => 'application.shared.components.S3AssetManager',
            'host' => S3_HOST,
            'bucket' => S3_BUCKET,
            'path' => 'assets', //or any other folder you want
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'site_url' => SITE_URL,
        'mongodb_ack_level' => MONGODB_ACK_LEVEL
    ),
    'theme' => FRONTEND_THEME,
);