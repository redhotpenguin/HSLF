<?php

function __autoload($class) {
    $parts = explode('\\', $class);
    $file = __DIR__ . '/WorkerLibrary/' . end($parts) . '.php';
    require $file;
}