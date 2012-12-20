<?php

class OrganizationsAPI extends APIBase {

    public function __construct() {
        parent::__construct(new Organization);
    }
}

?>
