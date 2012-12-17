<?php

class OptionsAPI extends APIBase implements IAPI {

    public function __construct() {
        parent::__construct(new Option);
    }

    public function requiresAuthentification() {
        return true;
    }

}

