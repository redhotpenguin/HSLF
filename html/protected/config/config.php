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


$site_url = 'http://mvg.winningmark.com';
$admin_email = 'jonas@winningmark.com';

$dbname = 'voterguide';


// DB Config
DEFINE('PROJECT_NAME', 'HSLF');
DEFINE('SITE_URL', $site_url);
DEFINE('ADMIN_EMAIL', $admin_email);
DEFINE('DB_NAME', $dbname);
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);

// Themes configs
DEFINE('ADMIN_THEME', 'hslf');
DEFINE('FRONTEND_THEME', 'hslf_frontend');