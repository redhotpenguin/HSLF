<?php

require_once('/var/www/html/mobile_platform/html/protected/helpers/globals.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/UrbanAirshipClient.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/PushNotification.php'); // there is a better way to do this..
require_once('/var/www/html/mobile_platform/html/protected/client/vendors/UrbanAirship/PushClient.php'); // there is a better way to do this..

/**
 * Unitary tests for the Push Client module
 *
 * @author Jonas
 */
class PushClientTest extends CDbTestCase {

    private $apiKey = "SOebz9QcSEmguGMiUKqj-Q"; // map-framework account https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/
    private $apiSecret = "PUbBlrsnQP-pkAoV8uPDSA"; // map-framework account  https://go.urbanairship.com/apps/SOebz9QcSEmguGMiUKqj-Q/api/

    public function testSendPushNotificationByTags() {
        $notification = new PushNotification("[UNIT TESTS] PushClientTest - testSendPushNotificationByTags");
        $tags = array('district_or_census_place_lake-oswego_4140550', 'tag2');

        $pushId = $this->getPushClient()->sendPushNotificationByTags($notification, $tags);

        $this->assertTrue($pushId !== false);
    }

    public function testSendBroadcastPushNotification() {
        $notification = new PushNotification('[UNIT TESTS] PushClientTest - testSendBroadcastPushNotification');
        $notification->setPayload(array('payload_id' => "123"));

        $pushId = $this->getPushClient()->sendBroadcastPushNotification($notification);

        $this->assertTrue($pushId !== false);
    }

    public function testSendPushNotificationBySegment() {
        $segmentId = "983c8acc-c576-47f9-9096-ac0a59e2ad11";

        $p = new PushNotification('[UNIT TESTS] PushClientTest - testSendPushNotificationBySegment');
        $p->setPayload(array('payload_id' => '123', 'p2' => 'bar'));

        $pushId = $this->getPushClient()->sendPushNotificationBySegment($p, $segmentId);


        $this->assertTrue($pushId !== false);
    }

    private function getPushClient() {
        return new PushClient($this->apiKey, $this->apiSecret);
    }

}

