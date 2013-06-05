<?php

/**
 * Unitary tests for DistrictResolver
 *
 * @author Jonas
 */
class DistrictResolverAPITest extends CDbTestCase {

    private $target = 'http://www.voterguide.com';
    private $tenant;
    private $tenantId = 1;
    

    public function __construct() {
        $this->tenant = Tenant::model()->findByPk($this->tenantId);
    }

    public function testGetDistrictsByAddress() {
        $result = $this->getResource('DistrictResolver/?address=4261%20glacier%20lily%20st%20lake%20oswego%20oregon&districts=legislative,nonlegislative/census');
        $this->log($result);
        $this->assertEquals(200, $result->status);
    }

    private function getResource($endPoint) {
        $url = $this->target.'/api/'.$this->tenantId.'/'.$endPoint;
        $session = curl_init($url);
        curl_setopt($session, CURLOPT_USERPWD, $this->tenant->api_key . ':' . $this->tenant->api_secret);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
        $content = curl_exec($session);

        $this->log($this->tenant->api_key . ':' . $this->tenant->api_secret);

        curl_close($session);

        return json_decode($content);
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

    private function dump($x) {
        ob_start();
        var_dump($x);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}