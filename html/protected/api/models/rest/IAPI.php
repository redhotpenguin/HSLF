<?php

interface IAPI {

    public function getList($tenantId, $arguments = array());

    public function getSingle($tenantId, $id, $arguments = array());

    public function create($tenantId, $arguments = array());

    public function update($tenantId, $id, $arguments = array());
    
    public function requiresAuthentification();
    
    /**
     * return cache duration in seconds
     */
    public function getCacheDuration();
    
}
