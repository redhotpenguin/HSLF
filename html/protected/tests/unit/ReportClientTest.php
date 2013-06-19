<?php

require_once('/var/www/html/mobile_platform/html/protected/helpers/globals.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/lib/UrbanAirshipClient.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/ReportClient.php'); // there is a better way to do this..

use UrbanAirship\lib\PushNotification as PushNotification;

/**
 * Unitary tests for the Push Client module
 *
 * @author Jonas
 */
class ReportClientTest extends CDbTestCase {

    private $apiKey = "SOebz9QcSEmguGMiUKqj-Q"; // map-framework account https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/
    private $apiSecret = "PUbBlrsnQP-pkAoV8uPDSA"; // map-framework account  https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/

    public function testGetPushReport() {
        $result = $this->getReportClient()->getPushReport("99d689d4-d793-11e2-92ce-d4bed9a887d4");
        $this->assertNotEmpty($result);
    }

    public function testGetCurrentMonthReport() {
        $result = $this->getReportClient()->getCurrentMonthReport();
        $this->assertNotEmpty($result);
    }

    public function testGetResponseReport() {
        $start = date("Y-m-01") . "%2000:00:00";
        $end = date("Y-m-t") . "%2023:59:59";
        $precision = "DAILY";
        $result = $this->getReportClient()->getResponseReport($start, $end, $precision);
        $this->assertNotEmpty($result);
    }

    private function getReportClient() {
        return new UrbanAirship\ReportClient($this->apiKey, $this->apiSecret);
    }

}

