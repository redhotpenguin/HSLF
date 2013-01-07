<?php


function __autoload($class)
{
    $parts = explode('\\', $class);
    require __DIR__.'/WorkerLibrary/'.end($parts) . '.php';
}