<?php

class TagsAPI extends APIBase implements IAPI {

    public function __construct() {

        $tag = new Tag();
        $tag->sessionTenantId = 1;
        parent::__construct($tag);
    }

}

?>
