<?php
require_once("config.php");

function worker_autoloader($class) {
    $parts = explode('\\', $class);
    $file = __DIR__ . '/WorkerLibrary/' . end($parts) . '.php';
    require_once ( $file );
}

spl_autoload_register('worker_autoloader');