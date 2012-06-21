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


// Project config
DEFINE('PROJECT_NAME', 'HSLF');
DEFINE('SITE_URL', 'http://mvg.winningmark.com');
DEFINE('ADMIN_EMAIL', 'mobile@winningmark.com');


// DB Config
DEFINE('DB_NAME', 'voterguide');
DEFINE('DB_HOST', $dbhost);
DEFINE('DB_USER', $dbuser);
DEFINE('DB_PASS', $dbpass);
DEFINE('DB_PORT', $dbport);

// Theme config
DEFINE('ADMIN_THEME', 'hslf');
DEFINE('FRONTEND_THEME', 'hslf_frontend');