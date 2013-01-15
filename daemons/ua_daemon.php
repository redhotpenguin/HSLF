#!/usr/bin/env php
<?php
    
    require_once("../worker/UAWorker.php");
    
    // This is mandatory to use the UNIX signal functions:
    // http://php.net/manual/en/function.pcntl-signal.php
    declare(ticks = 1);

    // A function to write on the error output
    function    warn($msg)
    {
        $stderr = fopen("php://stderr", "w+");
        fputs($stderr, $msg);
    }

    // Callback called when you run `supervisorctl stop'
    function    sigterm_handler($signo)
    {
        global $worker;
        warn("Exiting - killing worker!\n");
        unset($worker);
        exit(0);
    }

    function    main()
    {
        global $worker;
        $worker = new UAWorker();
        while (true) {
            
            if($worker->isHealthy() && $worker->processJob() === true ){
             //   warn('OK');
            }else{
                warn("Not healthy: please start mongodb,rabbitmq and restart this daemon.\n");
                sleep(5);
            }
           
            sleep(1);
        }
    }

    
    
    
    // Bind our callback on the SIGTERM signal and run the daemon
    pcntl_signal(SIGTERM, "sigterm_handler");
    main();

?>