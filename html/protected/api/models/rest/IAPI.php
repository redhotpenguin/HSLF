<?php

interface IAPI {

    public function getList($tenantAccountId, $arguments = array());
    
    public function getSingle($tenantAccountId, $id, $arguments = array());
    
    public function setAuthenticated($authenticated);
}
