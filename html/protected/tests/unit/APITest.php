<?php

/**
 * Unitary tests for the API
 *
 * @author Jonas
 */
class APITest extends CDbTestCase {

    private $target = 'http://www.voterguide.com';
    private $tenant;
    private $tenantId = 1;

    public function __construct() {
        $this->tenant = Tenant::model()->findByPk($this->tenantId);
    }

    public function testAlertTypesAPI() {
        $result = $this->getResource('AlertTypes');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testBallotItemsAPI() {
        $result = $this->getResource('BallotItems');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testContactsAPI() {
        $result = $this->getResource('Contacts');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testDistrictResolverAPI() {
        $result = $this->getResource('DistrictResolver/?address=4261%20glacier%20lily%20st%20lake%20oswego%20oregon&districts=legislative,nonlegislative/census', true);
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
        
        $result2 = $this->getResource('DistrictResolver/?lat=45.548294&long=-122.725525&districts=legislative,nonlegislative/census', true);
        $this->assertEquals(200, $result2->status);
        $this->assertNotEmpty($result2->results);
    }

    public function testItemNewsAPI() {
        $result = $this->getResource('ItemNews');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testOptionsAPI() {
        $result = $this->getResource('Options', true);
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testPayloadsAPI() {
        $result = $this->getResource('Payloads');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testPushMessagesAPI() {
        $result = $this->getResource('PushMessages');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    public function testTagsAPI() {
        $result = $this->getResource('Tags');
        $this->assertEquals(200, $result->status);
        $this->assertNotEmpty($result->results);
    }

    private function getResource($endPoint, $authRequired = false) {
        $url = $this->target . '/api/' . $this->tenantId . '/' . $endPoint;
        $session = curl_init($url);


        if ($authRequired) {
            curl_setopt($session, CURLOPT_USERPWD, $this->tenant->api_key . ':' . $this->tenant->api_secret);
        }
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