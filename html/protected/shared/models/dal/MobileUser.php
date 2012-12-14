<?php

// Class for mobile_user document
class MobileUser extends ActiveMongoDocument {

    public function __construct($fields = array()) {
        parent::__construct("mobile_user");
        $this->fields = $fields;
    }

}

