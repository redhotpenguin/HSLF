<?php

class GeoProviderTest extends CDbTestCase {

    private $ciceroProvider;

    public function __construct() {
        $providerFactory = new GeoCodingClientProvider();

        $this->ciceroProvider = $providerFactory->getGeoCodingClient('cicero');
    }

    public function testFactory() {

        $this->assertNotNull($this->ciceroProvider);
    }

    public function testGetDistrictIdsByAddress() {
        $this->log("testGetDistrictIdsByAddress");
        $districtIds = array();


        $districtIds = $this->ciceroProvider->getDistrictIdsByAddress("4261 glacier lily street, lake oswego, oregon");
        $this->log($districtIds);

        $this->assertNotEmpty($districtIds);
    }

    public function testGetDistrictIdsLatLong() {
        $this->log("testGetDistrictIdsByLatLong");
        $districtIds = array();


        $districtIds = $this->ciceroProvider->getDistrictIdsByLatLong("45.523451", "-122.676206991");
        $this->log($districtIds);

        $this->assertNotEmpty($districtIds);
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

}