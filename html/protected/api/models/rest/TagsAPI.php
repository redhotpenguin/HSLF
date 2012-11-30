<?php

class TagsAPI extends APIBase implements IAPI {

     public function __construct(){
         
        $tag = new Tag();
        $tag->sessionTenantAccountId = 1;
        parent::__construct( $tag );
    }

}

?>
