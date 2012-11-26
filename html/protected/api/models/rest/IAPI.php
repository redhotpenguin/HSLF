<?php

interface IAPI {

    public function getList($arguments = array());

    public function getListByCategory($category, $arguments = array());
    
    public function getSingle($id, $arguments = array());
}
