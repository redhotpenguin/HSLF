<?php

class UrbanAirshipNotifierTest extends CTestCase {

    private $notifier;
      private $device_token = '0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC';

    public function __construct() {
        $this->notifier = new UrbanAirshipNotifier();
    }

    public function _testSendPushNotifications_DROID_IOS() {
        $notifier = new UrbanAirshipNotifier();

        $app_user1 = new Application_users();
        $app_user1->setAttribute('type', 'android');
        $app_user1->setAttribute('device_token', 'fec7e94a-c9b6-4874-a5b3-c1626cd70355');

        $app_user2 = new Application_users();
        $app_user2->setAttribute('type', 'ios');
        $app_user2->setAttribute('device_token', '1374BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');

        $application_users = array(
            $app_user1,
            $app_user2,
        );

        $result = $notifier->sendPushNotifications($application_users, 'A Message');

        error_log(print_r($result, true));

        $this->assertEquals(true, $result['PUSH_ANDROID']);
        $this->assertEquals(true, $result['PUSH_IOS']);
    }

    public function _testSendPushNotifications_DROID_only() {
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

    public function  _testSendPushNotifications_IOS_ONLY() {
        $notifier = new UrbanAirshipNotifier();

        $app_user2 = new Application_users();
        $app_user2->setAttribute('type', 'ios');
        $app_user2->setAttribute('device_token', '1374BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');

        $application_users = array(
            $app_user2,
        );

        $result = $notifier->sendPushNotifications($application_users, 'IOS ONLY');

        error_log(print_r($result, true));

        $this->assertEquals(-1, $result['PUSH_ANDROID']);
        $this->assertEquals(true, $result['PUSH_IOS']);
    }

    public function _testNotify_district_users() {

        $district_ids = array(1, 4);
        $message = 'Testing Notify District Users';
        $notifier = new UrbanAirshipNotifier();

        $result = $notifier->notify_district_users($district_ids, $message);

        error_log(print_r($result, true));


        $this->assertEquals(true, $result);
    }

    public function _testSendBroadcastNotification() {
        $message = 'broadcasting to both ios and droids';

        $result = $this->notifier->sendBroadcastNotification($message);
        error_log(print_r($result, true));
        $this->assertEquals(true, $result['BROADCAST_IOS']);
        $this->assertEquals(true, $result['BROADCAST_ANDROID']);
    }
    
    public function testDeleteDeviceTag(){
        $test_result = $this->notifier->delete_device_tag('hello', $this->device_token, 'ios');
        $this->assertEquals(true, $test_result);
    }
    
    public function  _testAddDeviceTag(){
        $test_result = $this->notifier->add_device_tag('hello', $this->device_token, 'ios');
        $this->assertEquals(true, $test_result);
    }
    
    
     public function  testAddWrongDeviceTag(){
        $test_result = $this->notifier->add_device_tag('hello', $this->device_token.'abc', 'ios');
        $this->assertEquals(false, $test_result);
    }

}

?>
