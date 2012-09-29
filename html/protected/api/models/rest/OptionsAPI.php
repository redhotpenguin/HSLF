<?php

class OptionsAPI extends APIBase implements IAPI {
    
    public function __construct(){
        // turn on authentification check
        parent::__construct(new Option, true );
    }

}

