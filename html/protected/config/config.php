<?php

$env = json_decode(file_get_contents("/home/dotcloud/environment.json"), true);

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
    $dbport = '5444';

set_include_path(get_include_path() . PATH_SEPARATOR . '/home/dotcloud/php-env/share/php');



// Image File Manager conf:
// please also update root path in protected/extensions/tinymce/assets/jscripts/tiny_mce/plugins/imagemanager/config.php
// Project config
DEFINE('PROJECT_NAME', 'Our Oregon');
//DEFINE('SITE_URL', 'http://mvg.winningmark.com');
DEFINE('SITE_URL', 'http://www.voterguide.com');
DEFINE('ADMIN_EMAIL', 'mobile@winningmark.com');


// DB Config
DEFINE('DB_NAME', 'ouroregon');
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);

// API Config
DEFINE('API_KEY', '52356');
DEFINE('API_SECRET', 'PqiW_IDKL3mFi_OirCqOe-u');

// Urban Airship
DEFINE('UA_API_KEY', 'ouRCLPaBRRasv4K1AIw-xA');
DEFINE('UA_API_SECRET', '7hd19C6rSzyrbKM3k6KqDg'); // master secret
DEFINE('UA_DASHBOARD_LINK', 'https://go.urbanairship.com/apps/3rQVdPPdT7Osvdx1vB37Tg/composer/rich-push/');

// Cicero Config
DEFINE('CICERO_USERNAME', 'winningmark');
DEFINE('CICERO_PASSWORD', '3TUuAv5DwNsB');



// Theme config
DEFINE('ADMIN_THEME', 'dashboard');
DEFINE('FRONTEND_THEME', 'frontend');