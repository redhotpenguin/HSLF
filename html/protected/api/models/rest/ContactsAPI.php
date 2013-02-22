<?php

class ContactsAPI extends APIBase {

    public function __construct() {
        parent::__construct(new Contact);
    }
}