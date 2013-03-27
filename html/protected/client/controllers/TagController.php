<?php

class TagController extends CrudController {

    public function __construct() {
        parent::__construct('tag');
        $this->setModel( new Tag );
        $this->setFriendlyModelName('Tag');
    }

}
