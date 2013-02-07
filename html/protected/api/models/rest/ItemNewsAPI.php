<?php


class ItemNewsAPI extends APIBase{
    public function __construct(){
        parent::__construct( new ItemNews );
    }
}