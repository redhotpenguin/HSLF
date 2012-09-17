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

    set_include_path(get_include_path() . PATH_SEPARATOR . '/home/dotcloud/php-env/share/php');
} else {    //dev server conf
    $dbhost = 'localhost';
    $dbname = 'ouroregon_dev';
    $dbuser = 'postgres';
    $dbpass = 'pengu1n';
    $dbport = '5444';
    $siteurl = 'http://www.voterguide.com';
    $uploaddir = '/../content/img'; // physical path
    $uploadpath = '/content/img'; // wwww path
}


// Image File Manager conf:
// please also update root path in protected/extensions/tinymce/assets/jscripts/tiny_mce/plugins/imagemanager/config.php
// Project config
DEFINE('PROJECT_NAME', 'Our Oregon');
DEFINE('SITE_URL', $siteurl);
DEFINE('ADMIN_EMAIL', 'mobile@winningmark.com');
DEFINE('UPLOAD_DIR', $uploaddir);
DEFINE('UPLOAD_PATH', $uploadpath);


// DB Config
DEFINE('DB_NAME', $dbname);
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);

// API Config
DEFINE('API_KEY', '52356');
DEFINE('API_SECRET', 'PqiW_IDKL3mFi_OirCqOe-u');

// Urban Airship
DEFINE('UA_API_KEY', '3rQVdPPdT7Osvdx1vB37Tg'); // Our Oregon credentials
DEFINE('UA_API_SECRET', 'Iy380QDRQbCALRrgDB_8Qw'); // master secret
DEFINE('UA_DASHBOARD_LINK', 'https://go.urbanairship.com/apps/3rQVdPPdT7Osvdx1vB37Tg/composer/rich-push/');

// Cicero Config
DEFINE('CICERO_USERNAME', 'winningmark');
DEFINE('CICERO_PASSWORD', '3TUuAv5DwNsB');

// Theme config
DEFINE('ADMIN_THEME', 'dashboard');
DEFINE('FRONTEND_THEME', 'frontend');