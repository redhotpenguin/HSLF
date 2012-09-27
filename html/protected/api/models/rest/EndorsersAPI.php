<?php

class EndorsersAPI extends APIBase {

    public function __construct() {
        parent::__construct(new Endorser);
    }

}

?>
