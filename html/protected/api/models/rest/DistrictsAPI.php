<?php

class DistrictsAPI extends APIBase implements IAPI {

    public function __construct(){
        parent::__construct( new District );
    }
}
