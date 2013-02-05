<?php


class PayloadsAPI  extends APIBase {

    public function __construct() {
        parent::__construct(new Payload);
    }
}

