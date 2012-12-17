<?php

interface IAPI {

    public function getList($tenantId, $arguments = array());

    public function getSingle($tenantId, $id, $arguments = array());

    public function create($tenantId, $arguments = array());

    public function setAuthenticated($authenticated);
}
