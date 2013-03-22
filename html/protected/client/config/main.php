<?php

Yii::setPathOfAlias('bootstrap', dirname(__FILE__) . '/../../shared/extensions/bootstrap');

/* BACKEND CONFIG FILE */

$clientDirectory = dirname(dirname(__FILE__));
$appDirectory = dirname($clientDirectory);

Yii::setPathOfAlias('backend', $clientDirectory);

return array(
    'catchAllRequest' => (MAINTENANCE === 'on' ? array('site/maintenance') : null),
    'basePath' => $appDirectory,
    'controllerPath' => $clientDirectory . '/controllers',
    'viewPath' => $clientDirectory . '/views/',
    'runtimePath' => $clientDirectory . '/runtime',
    'name' => 'Admin Dashboard - Winning Mark Mobile ',
    'preload' => array('bootstrap'), // preload the bootstrap component),
    'import' => array(
        'application.shared.extensions.directmongosuite.components.*',
        'backend.extensions.*',
        'backend.models.*',
        'backend.models.behaviors.*',
        'application.shared.models.dal.*', // data access logic classes
        'application.shared.models.bll.*', // business  logic classes
        'backend.components.*',
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
            'schemaCachingDuration' => 3600,
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
                'client/logout' => 'site/logout',
                'client/settings' => 'user/settings',
                'client/tenant/<_action>' => 'tenant/<_action>',
                'client/state/<_action>' => 'state/<_action>',
                'client/district/<_action>' => 'district/<_action>',
                'client/office/<_action>' => 'office/<_action>',
                'client/party/<_action>' => 'party/<_action>',
                'client/recommendation/<_action>' => 'recommendation/<_action>',
                'client/user/<_action>' => 'user/<_action>',
                'client/import/<_action>' => 'import/<_action>',
                'client' => 'site/index',
                'client/error' => 'site/error',
                'client/<tenant_name>' => 'site/home',
                // dynamic rules
                'client/<tenant_name>/<_controller>' => '<_controller>',
                'client/<tenant_name>/<_controller>/<_action>' => '<_controller>/<_action>',
                array(
                    'class' => 'backend.components.TenantUrlRule',
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
        'queue' => array(
            'class' => 'backend.vendors.Queue.Queue',
            'redis_host' => REDIS_QUEUE_HOST,
            'redis_port' => REDIS_QUEUE_PORT,
            'redis_password' => REDIS_QUEUE_PASSWORD,
            'redis_db' => REDIS_QUEUE_DB,
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
    'params' => array(
        'dateFormat' => 'Y-m-d H:i:s',
        'site_url' => SITE_URL,
        'upload_path' => SITE_URL . UPLOAD_PATH,
        'upload_dir' => UPLOAD_DIR,
        'mongodb_ack_level' => MONGODB_ACK_LEVEL,
        'mongodb_host' => MONGODB_HOST,
        'mongodb_name' => MONGODB_NAME,
        'mongodb_user' => MONGODB_USER,
        'mongodb_password' => MONGODB_PASS,
    ),
    'behaviors' => array(
        'edms' => array(
            'class' => 'EDMSBehavior'
        )
    ),
    'theme' => ADMIN_THEME,
);