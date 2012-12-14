<?php

// Experimental class  - uses MongoDb for storage (no schema!)
// represent mobile_user document
// @todo: massive unit testing!
class MobileUser extends ActiveMongoDocument {

    public function __construct($fields = array()) {
        parent::__construct("mobile_user");
        $this->fields = $fields;
    }

}

