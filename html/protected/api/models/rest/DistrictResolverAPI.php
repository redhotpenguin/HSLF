<?php

/*
 * District Resolver endpoint: /api/<tenantId>/DistrictResolver
 * Uses Cicero to get districts matching a location
 * Requires HTTP authentification
 */

class DistrictResolverAPI implements IAPI {

    private $cacheDuration;
    private $geoCodingClient;

    public function __construct($tenantId) {
        $geoCodingCLientProvider = new GeoCodingClientProvider($tenantId);
        $this->geoCodingClient = $geoCodingCLientProvider->getGeoCodingClient('cicero');
        $this->cacheDuration = Yii::app()->params->normal_cache_duration;
    }

    public function create($tenantId, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

    /**
     * /api/n/DistrictResolver/?address=bla bla
     * alternative:  /api/n/DistrictResolver/?lat=123&long=456
     * required: &district=legislative,nonlegislative/county,nonlegislative/school,nonlegislative/census
     * 
     * @param integer $tenantId tenant id
     * @param array $arguments options
     * @return array of districts
     * 
     */
    public function getList($tenantId, $arguments = array()) {

        $cacheKey = APIBase::cacheKeyBuilder('DistrictResolver', $tenantId, $arguments);
        // serve from cache?
        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }


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

        if (isset($arguments['address'])) {
            $location['address'] = $arguments['address'];
        } else {
            $location['lat'] = $arguments['lat'];
            $location['long'] = $arguments['long'];
        }


        // parse districts
        $encodedDistricts = strtolower($arguments['districts']);

        $districts = explode(',', $encodedDistricts);


        $resolvedDistricts = array();

        // retrieve districts that match the specified arguments
        foreach ($districts as $district) {
            $explodedDistrict = explode('/', $district);

            if (isset($explodedDistrict[1]))
                $districtType = $explodedDistrict[1];
            else
                $districtType = null;


            if ($explodedDistrict[0] == 'legislative') {
                $data = $this->getLegislativeDistricts($location);
                if ($data && !empty($data))
                    $resolvedDistricts = array_merge($resolvedDistricts, $data);
            } elseif ($explodedDistrict[0] == 'nonlegislative') {

                $data = $this->getNonLegislativeDistricts($location, $districtType);
                if ($data && !empty($data))
                    $resolvedDistricts = array_merge($resolvedDistricts, $data);
            } else {
                continue;
            }
        }

        if (!empty($resolvedDistricts)) {
            Yii::app()->cache->set($cacheKey, $resolvedDistricts, $this->cacheDuration);
        }

        return $resolvedDistricts;
    }

    private function getLegislativeDistricts($location) {


        $options = array('requesting' => 'legislative_district');

        if (isset($location['address'])) {
            return $this->geoCodingClient->getDistrictIdsByAddress($location['address'], $options);
        } elseif (isset($location['lat']) && isset($location['long'])) {
            return $this->geoCodingClient->getDistrictIdsByLatLong($location['lat'], $location['long'], $options);
        }
    }

    private function getNonLegislativeDistricts($location, $districtType) {
        $options = array(
            'requesting' => 'nonlegislative_district',
            'type' => $districtType
        );

        if (isset($location['address'])) {
            return $this->geoCodingClient->getDistrictIdsByAddress($location['address'], $options);
        } else {
            return $this->geoCodingClient->getDistrictIdsByLatLong($location['lat'], $location['long'], $options);
        }
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

    public function requiresAuthentification() {
        return true;
    }

    public function update($tenantId, $id, $arguments = array()) {
        return new RestFailure(RestFailure::HTTP_NOT_IMPLEMENTED_ERROR_CODE);
    }

}