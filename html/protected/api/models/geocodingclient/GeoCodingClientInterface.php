<?php

interface GeoCodingClientInterface {
    
    public function __construct(HttpRequestClientInterface $httpRequestClient, $options = array());
    
    public function getDistrictIdsByAddress($address, $options = array());
    
    public function getDistrictIdsByLatLong($lat,$long, $options = array());
}
