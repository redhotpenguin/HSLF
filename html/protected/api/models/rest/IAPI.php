<?php

interface IAPI {

    public function getList($arguments = array());
    
    public function getPartialList();

    public function getSingle($id);
}
