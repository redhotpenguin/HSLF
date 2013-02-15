<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../../shared/extensions/bootstrap');

/* BACKEND CONFIG FILE */

$backendDirectory = dirname(dirname(__FILE__));
$appDirectory = dirname($backendDirectory);

Yii::setPathOfAlias('admin', $backendDirectory);


return array(
    'basePath' => $appDirectory,
    'controllerPath' => $backendDirectory . '/controllers',
    'viewPath' => $backendDirectory . '/views/',
    'runtimePath' => $backendDirectory . '/runtime',
    'name' => 'Admin Dashboard - Winning Mark Mobile ',
    'preload' => array('bootstrap'), // preload the bootstrap component),
    'import' => array(
        'application.shared.extensions.directmongosuite.components.*',
        'admin.extensions.*',
        'admin.models.*',
        'admin.models.behaviors.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'admin.components.*',
        'application.models.*',
        'application.shared.components.*',
    ),
    'modules' => array(),
    'components' => array(
        'bootstrap' => array(
            'class' => 'bootstrap.components.Bootstrap',
        ),
        'db' => array(
            'connectionString' => "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
            'emulatePrepare' => true,
            'username' => DB_USER,
            'password' => DB_PASS,
            'charset' => 'UTF-8',
            'schemaCachingDuration' => '60',
        ),
        'user' => array(
// enable cookie-based authentication
            'allowAutoLogin' => true,
            'class' => 'WebUser',
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'autoCreateSessionTable' => false,
            'autoCreateSessionTable' => false,
            'connectionID' => 'db',
            'sessionTableName' => 'user_session',
            // 'sessionName' => 'winningmarkmobile', // cookie name
            'cookieMode' => 'only', // only use cookies 
            'useTransparentSessionID' => false, // disable cookie less sessions,
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            // mapping
            'rules' => array(
// hard coded routes that conflict with tenancy
                'admin/logout' => 'site/logout',
                'admin/settings' => 'user/settings',
                'admin/tenant/<_action>' => 'tenant/<_action>',
                'admin/state/<_action>' => 'state/<_action>',
                'admin/district/<_action>' => 'district/<_action>',
                'admin/office/<_action>' => 'office/<_action>',
                'admin/party/<_action>' => 'party/<_action>',
                'admin/recommendation/<_action>' => 'recommendation/<_action>',
                'admin/user/<_action>' => 'user/<_action>',
                'admin/import/<_action>' => 'import/<_action>',
                'admin' => 'site/index',
                'admin/<tenant_name>' => 'site/home',
                // dynamic rules
                'admin/<tenant_name>/<_controller>' => '<_controller>',
                'admin/<tenant_name>/<_controller>/<_action>' => '<_controller>/<_action>',
                array(
                    'class' => 'admin.components.TenantUrlRule',
                ),
            ),
        ),
        'authManager' => array(// rbac config
            'class' => 'MyCDbAuthManager',
            'connectionID' => 'db',
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
        's3' => array(
            'class' => 'application.shared.extensions.S3.ES3',
            'aKey' => S3_AKEY,
            'sKey' => S3_SKEY,
        ),
        'assetManager' => array(
            'class' => 'S3AssetManager',
            'host' => S3_HOST,
            'bucket' => S3_BUCKET,
            'path' => 'assets', //or any other folder you want
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
        'mongodb_ack_level' => MONGODB_ACK_LEVEL,
    ),
    'behaviors' => array(
        'edms' => array(
            'class' => 'EDMSBehavior'
        )
    ),
    'theme' => ADMIN_THEME,
);