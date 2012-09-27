<?php

class StatesAPI extends APIBase {

    public function __construct(){
        parent::__construct( new State );
    }

}
