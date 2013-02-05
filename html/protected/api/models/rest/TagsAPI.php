<?php

class TagsAPI extends APIBase{

    public function __construct() {
        parent::__construct(new Tag);
    }

}