<?php

/*
 * District Resolver endpoint: /api/<tenantId>/DistrictResolver
 * Uses Cicero to get districts matching a location
 * Requires HTTP authentification
 */

class DistrictResolverAPI implements IAPI {

    public function create($tenantId, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

    public function getList($tenantId, $arguments = array()) {
        /*
         * /api/n/DistrictResolver/?address=bla bla
         * alternative:  /api/n/DistrictResolver/?lat=123&long=456
         * 
         * &district=legislative,nonlegislative/county,nonlegislative/school,nonlegislative/census
         * 
         */

        // user proofing:
        //  address or lat AND long must be set
        if (!isset($arguments['address']) && (!isset($arguments['lat']) || !isset($arguments['long']) )) {
            $failure = new RestFailure(RestFailure::HTTP_BAD_REQUEST_CODE);
            $failure->setReason("An address or a lat/long coordinate is missing.");
            return $failure;
        }
        

        // check districts
        if (!isset($arguments['districts'])) {
            $failure = new RestFailure(RestFailure::HTTP_BAD_REQUEST_CODE);
            $failure->setReason("A list of district types are missing.");
            return $failure;
        }
        
        
        // format location
        $location = array();
        
        if($arguments['address']){
            $location['address'] = $arguments['address'];
        }
        else{
            $location['latitude'] = $arguments['lat'];
            $location['longitude'] = $arguments['long'];
        }
            
        
        // parse districts
        $encodedDistricts = strtolower($arguments['districts']);
        
        $districts = explode(',', $encodedDistricts);
        
        
        $resolvedDistricts = array();
        
        foreach($districts as $district){
             $explodedDistrict = explode('/',$district);
             
             if(isset($explodedDistrict[1]))
                $districtType = $explodedDistrict[1];
             else
                 $districtType = null;
             
             
             if($explodedDistrict[0] == 'legislative'){
                 
                 array_push($resolvedDistricts, $this->getLegislativeDistrict($location));
                 
             }elseif($explodedDistrict[0] == 'nonlegislative'){
                 
                  array_push($resolvedDistricts, $this->getNonLegislativeDistrict($location, $districtType));
                  
             }
             else{
                 continue;
             }
                
        }
        
        
        
        return $resolvedDistricts;
        
    }
    
    private function getLegislativeDistrict($location){
        return ['leg1', 'leg2'];
    }
    
    private function getNonLegislativeDistrict($location, $districtType){
        return ['nonleg4', 'nonleg514'];
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