<?php

$dotcloud_conf = "/home/dotcloud/environment.json";

if (file_exists($dotcloud_conf)) {     // prod server conf
    $env = json_decode(file_get_contents($dotcloud_conf), true);
    $dbhost = $env['DOTCLOUD_DB_SQL_HOST'];
    $dbname = 'ouroregon';
    $dbuser = $env['DOTCLOUD_DB_SQL_LOGIN'];
    $dbpass = $env['DOTCLOUD_DB_SQL_PASSWORD'];
    $dbport = $env['DOTCLOUD_DB_SQL_PORT'];
    $siteurl = 'http://oo.m.winningmark.com';
    $uploaddir = '/../content/img'; // physical path
    $uploadpath = '/content/img'; // wwww path
    $shareurl = 'http://vote.ouroregon.org';

    set_include_path(get_include_path() . PATH_SEPARATOR . '/home/dotcloud/php-env/share/php');
} else {    //dev server conf
    $dbhost = 'localhost';
    $dbname = 'mobile_platform';
    $dbuser = 'postgres';
    $dbpass = 'pengu1n';
    $dbport = '5432';
    $siteurl = 'http://www.voterguide.com';
    $uploaddir = '/../content/img'; // physical path
    $uploadpath = '/content/img'; // wwww path
    $shareurl = 'http://vote.ouroregon.org';
}

// DB Config
DEFINE('DB_NAME', $dbname);
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);

// Theme config
DEFINE('ADMIN_THEME', 'dashboard');
DEFINE('FRONTEND_THEME', 'frontend');

DEFINE('UPLOAD_DIR', $uploaddir);
DEFINE('UPLOAD_PATH', $uploadpath);
DEFINE('SITE_URL', $siteurl);



// Image File Manager conf:
// please also update root path in protected/extensions/tinymce/assets/jscripts/tiny_mce/plugins/imagemanager/config.php
// 
