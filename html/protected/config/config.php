<?php

$dotcloud_conf = "/home/dotcloud/environment.json";

if (file_exists($dotcloud_conf)) {     // dotcloud server conf
    $env = json_decode(file_get_contents($dotcloud_conf), true);
    $dbname = 'mobile_advocacy_platform';

    $dbhost = $env['DOTCLOUD_DB_SQL_HOST'];
    $dbuser = $env['DOTCLOUD_DB_SQL_LOGIN'];
    $dbpass = $env['DOTCLOUD_DB_SQL_PASSWORD'];
    $dbport = $env['DOTCLOUD_DB_SQL_PORT'];


    if ($env['DOTCLOUD_PROJECT'] === 'productionmap') {     // production specific config
        $mongodbhost = $env['DOTCLOUD_DATA_MONGODB_URL'] . '/?replicaSet=productionmap.data';
        $siteurl = 'http://www.winningmarkmobile.com';
        $s3Host = 'wmmobile.s3.amazonaws.com';
        $s3Bucket = 'wmmobile';
    } else { // sandbox specific config
        $mongodbhost = $env['DOTCLOUD_DATA_MONGODB_URL'];
        $siteurl = $env['DOTCLOUD_WWW_HTTP_URL'];
        $s3Host = 'mobileadvocacydev.s3.amazonaws.com';
        $s3Bucket = 'mobileadvocacydev';
    }

    $mongodbname = "mobile_advocacy_platform";
    $mongodbuser = "map_user"; // mongo user is set using the mongo shell in dotcloud
    $mongdbpass = "jeMEwRArEKwBg7Q"; // same as above
    $mongodbacklevel = 1;


    $uploaddir = '/../content/img'; // physical path
    $uploadpath = '/content/img'; // wwww path
    $shareurl = 'http://vote.ouroregon.org';

    $redisHost = $env['DOTCLOUD_CACHE_REDIS_HOST'];
    $redisPort = $env['DOTCLOUD_CACHE_REDIS_PORT'];
    $redisLogin = $env['DOTCLOUD_CACHE_REDIS_LOGIN'];
    $redisPassword = $env['DOTCLOUD_CACHE_REDIS_PASSWORD'];
    $redisDatabase = '1'; // redis does not use name for db's

    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';


    $apiShortCacheDuration = 10;
    $apiNormalCacheDuration = 100;
    $apiLongCacheDuration = 3600;

    $debug = true;
    
    set_include_path(get_include_path() . PATH_SEPARATOR . '/home/dotcloud/php-env/share/php');
} else {    //dev server conf
    $dbhost = '127.0.0.1';
    $dbname = 'mobile_advocacy_platform';
    $dbuser = 'postgres';
    $dbpass = 'pengu1n';
    $dbport = '5432';

    $mongodbhost = "mongodb://localhost:27017";
    $mongodbname = "mobile_advocacy_platform";
    $mongodbuser = "admin";
    $mongdbpass = "admin";
    $mongodbacklevel = 1;

    $siteurl = ( isset($_SERVER['SERVER_NAME']) ? 'http://' . $_SERVER['SERVER_NAME'] : 'http://www.voterguide.com' ); // necessary since SERVER_NAME is not set during unit tests

    $uploaddir = '/../content/img'; // physical path
    $uploadpath = '/content/img'; // wwww path
    $shareurl = 'http://vote.ouroregon.org';

    $redisHost = '127.0.0.1';
    $redisPort = 6379;
    $redisLogin = 'guest';
    $redisPassword = 'foobared';
    $redisDatabase = '1';


    $s3AKey = 'AKIAIDNK7VPB47DB2F2Q';
    $s3SKey = '2F7TBdQsokQVpIZAgNUx/PgKyE01wz3AXLmGFYvh';
    $s3Host = 'maplocal.s3.amazonaws.com';
    $s3Bucket = 'maplocal';

    $apiShortCacheDuration = 10;
    $apiNormalCacheDuration = 100;
    $apiLongCacheDuration = 3600;

    $debug = true;
}

// REMOVE THIS WHEN LIVE
define('YII_DEBUG', $debug);



// DB Config
DEFINE('DB_NAME', $dbname);
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);


// MongoDB Config
DEFINE('MONGODB_HOST', $mongodbhost);
DEFINE('MONGODB_NAME', $mongodbname);
DEFINE('MONGODB_USER', $mongodbuser);
DEFINE('MONGODB_PASS', $mongdbpass);
DEFINE('MONGODB_ACK_LEVEL', $mongodbacklevel);

// Redis Config
DEFINE('REDIS_HOST', $redisHost);
DEFINE('REDIS_PORT', $redisPort);
DEFINE('REDIS_LOGIN', $redisLogin);
DEFINE('REDIS_PASSWORD', $redisPassword);
DEFINE('REDIS_DATABASE', $redisDatabase);

// S3 Config
DEFINE('S3_AKEY', $s3AKey);
DEFINE('S3_SKEY', $s3SKey);
DEFINE('S3_HOST', $s3Host);
DEFINE('S3_BUCKET', $s3Bucket);

// Project config
DEFINE('ADMIN_THEME', 'dashboard');
DEFINE('FRONTEND_THEME', 'frontend');
DEFINE('UPLOAD_DIR', $uploaddir);
DEFINE('UPLOAD_PATH', $uploadpath);
DEFINE('SITE_URL', $siteurl);

// API Cache duration values
DEFINE('API_SHORT_CACHE_DURATION', $apiShortCacheDuration);
DEFINE('API_NORMAL_CACHE_DURATION', $apiNormalCacheDuration);
DEFINE('API_LONG_CACHE_DURATION', $apiLongCacheDuration);
