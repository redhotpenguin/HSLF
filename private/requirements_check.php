<?php

$extensions = array(
    "amqp",
    "mongo",
    "pgsql",
    "apc",
    "curl",
    "pdo",
    "pdo_pgsql",
    "session",
    "json",
    "reflection",
    
);

$extensions_avalaible = array();

$extensions_missing = array();

foreach ($extensions as $extension) {
    if (extension_loaded($extension)) {
        array_push($extensions_avalaible, $extension);
    } else {
        array_push($extensions_missing, $extension);
    }
}

if(empty($extensions_missing)){
    printf("No extensions missing \n");
}
else{
    printf("Missing extensions: \n");
    foreach($extensions_missing as $ex){
        printf("%s\n", $ex);
    }
}

