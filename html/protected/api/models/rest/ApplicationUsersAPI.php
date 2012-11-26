<?php

class ApplicationUsersAPI extends APIBase{
    
    public function __construct(){
        // turn on authentification check
        parent::__construct(new Application_user, true );
    }

}