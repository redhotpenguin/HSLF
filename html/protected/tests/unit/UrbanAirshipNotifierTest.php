<?php

class UrbanAirshipNotifierTest extends CTestCase {

    private $notifier;

    public function __construct() {
        $this->notifier = new UrbanAirshipNotifier();
    }

    public function testSendPushNotifications_DROID_IOS() {
        $notifier = new UrbanAirshipNotifier();

        $app_user1 = new Application_users();
        $app_user1->setAttribute('type', 'android');
        $app_user1->setAttribute('device_token', 'fec7e94a-c9b6-4874-a5b3-c1626cd70355');

        $app_user2 = new Application_users();
        $app_user2->setAttribute('type', 'ios');
        $app_user2->setAttribute('device_token', '0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');

        $application_users = array(
            $app_user1,
            $app_user2,
        );

        $result = $notifier->sendPushNotifications($application_users, 'A Message');

        error_log(print_r($result, true));

        $this->assertEquals(true, $result['PUSH_ANDROID']);
        $this->assertEquals(true, $result['PUSH_IOS']);
    }

    public function testSendPushNotifications_DROID_only() {
        $notifier = new UrbanAirshipNotifier();


        $app_user1 = new Application_users();
        $app_user1->setAttribute('type', 'android');
        $app_user1->setAttribute('device_token', 'fec7e94a-c9b6-4874-a5b3-c1626cd70355');


        $application_users = array(
            $app_user1,
        );

        $result = $notifier->sendPushNotifications($application_users, 'DROID ONLY');

        error_log(print_r($result, true));

        $this->assertEquals(true, $result['PUSH_ANDROID']);
        $this->assertEquals(-1, $result['PUSH_IOS']);
    }

    public function testSendPushNotifications_IOS_ONLY() {
        $notifier = new UrbanAirshipNotifier();

        $app_user2 = new Application_users();
        $app_user2->setAttribute('type', 'ios');
        $app_user2->setAttribute('device_token', '0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');

        $application_users = array(
            $app_user2,
        );

        $result = $notifier->sendPushNotifications($application_users, 'IOS ONLY');

        error_log(print_r($result, true));

        $this->assertEquals(-1, $result['PUSH_ANDROID']);
        $this->assertEquals(true, $result['PUSH_IOS']);
    }

    public function testNotify_district_users() {

        $district_ids = array(1, 4);
        $message = 'Testing Notify District Users';
        $notifier = new UrbanAirshipNotifier();

        $result = $notifier->notify_district_users($district_ids, $message);

        error_log(print_r($result, true));


        $this->assertEquals(true, $result);
    }

    public function testSendBroadcastNotification() {
        $message = 'broadcasting to both ios and droids';

        $result = $this->notifier->sendBroadcastNotification($message);
        error_log(print_r($result, true));
        $this->assertEquals(true, $result['BROADCAST_IOS']);
        $this->assertEquals(true, $result['BROADCAST_ANDROID']);
    }

}

?>
