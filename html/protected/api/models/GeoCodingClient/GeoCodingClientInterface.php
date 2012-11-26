<?php

interface GeoCodingClientInterface {
    
    public function __construct(HttpRequestClientInterface $httpRequestClient);
    
    public function getDistrictsByAddress($address, $options = array());
    
    public function getDistrictsByLatLong($lat,$long, $options = array());
}

?>
