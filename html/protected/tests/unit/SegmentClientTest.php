<?php

require_once('/var/www/html/mobile_platform/html/protected/helpers/globals.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/lib/UrbanAirshipClient.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/lib/Segment.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/SegmentClient.php'); // there is a better way to do this..

/**
 * Unitary tests for the Push Client module
 *
 * @author Jonas
 */
class SegmentClientTest extends CDbTestCase {

    private $apiKey = "SOebz9QcSEmguGMiUKqj-Q"; // map-framework account https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/
    private $apiSecret = "PUbBlrsnQP-pkAoV8uPDSA"; // map-framework account  https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/

    public function _testGetSegments() {

        $result = $this->getSegmentClient()->getSegments();

        $this->assertNotEmpty($result);
    }

    public function testGetSegment() {

        $segmentId = '954bb924-6dff-4741-9897-594aad05b4f0'; // potland district segment id

        $result = $this->getSegmentClient()->getSegment($segmentId);

        $this->assertNotEmpty($result);
    }

    public function testGetTags() {

        $segmentId = 'cc3d595f-f8e7-4925-b2b0-7083ba17bc3e'; // complex tag segments

        $result = $this->getSegmentClient()->getSegment($segmentId)->getTags();

        $this->assertNotEmpty($result);

        $this->assertTrue(in_array('district__census_urban-area_portland-or--wa_71317', $result));
    }

    private function getSegmentClient() {
        return new UrbanAirship\SegmentClient($this->apiKey, $this->apiSecret);
    }

}

