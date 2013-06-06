<?php

/*
 * District Resolver endpoint: /api/<tenantId>/DistrictResolver
 * Uses Cicero to get districts matching a location
 * Requires HTTP authentification
 */

class DistrictResolverAPI implements IAPI {

    private $geoCodingClient;

    public function __construct() {
        $geoCodingCLientProvider = new GeoCodingClientProvider(Yii::app()->params['current_tenant_id']);
        $this->geoCodingClient = $geoCodingCLientProvider->getGeoCodingClient('cicero');
    }

    public function create($tenantId, $arguments = array()) {
        throw new RestException(501);
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
        // user proofing:
        //  address or lat AND long must be set
        if (!isset($arguments['address']) && (!isset($arguments['lat']) || !isset($arguments['long']) )) {
            throw new RestException(400, "An address or a lat/long coordinate is missing");
        }


        // check districts
        if (!isset($arguments['districts'])) {
            throw new RestException(400, "A list of district types are missing.");
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
        throw new RestException(501);
    }

    public function requiresAuthentification() {
        return true;
    }

    public function update($tenantId, $id, $arguments = array()) {
        throw new RestException(501);
    }

    public function getCacheDuration() {
        return Yii::app()->params->normal_cache_duration;
    }

}