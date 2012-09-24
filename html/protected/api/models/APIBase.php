<?php

abstract class APIBase {

    const AUTH_REQUIRED = 'authentication required';

    protected $isAuthenticated;

    public final function setAuthenticated($authenticated) {
        $this->isAuthenticated = $authenticated;
    }

 

}

?>
