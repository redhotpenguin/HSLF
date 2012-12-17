<?php

class TagsAPI extends APIBase implements IAPI {

    public function __construct() {
        parent::__construct(new Tag);
    }

}

?>
