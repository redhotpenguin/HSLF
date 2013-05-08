<?php

class DistrictResolverAPI implements IAPI{
    public function create($tenantId, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

    public function getList($tenantId, $arguments = array()) {
         
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

    public function requiresAuthentification() {
        return false;
    }

    public function update($tenantId, $id, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }
}