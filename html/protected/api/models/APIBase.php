<?php

abstract class APIBase {

    const AUTH_REQUIRED = 'authentication required';

    protected $authenticated;

    public final function setAuthenticated($authenticated) {
        $this->authentidcated = $authenticated;
    }

    protected final function isAuthenticated() {
        return $this->authenticated;
    }

}

?>
