<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class UrbanAPITest extends CTestCase {

    private $airship;
    private $api_key = 'ouRCLPaBRRasv4K1AIw-xA';
    private $api_secret = '7hd19C6rSzyrbKM3k6KqDg';
    private $device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';

    public function __construct() {
        $this->airship = new Airship($this->api_key, $this->api_secret);
    }

    public function _testBroadcastIos() {
        $message = 'Did you get that broadcast message?';
        $broadcast_message = array(
            'aps' => array('alert' => $message)
        );

        try {
            $result = $this->airship->broadcast_ios($broadcast_message);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        error_log($result);

        $this->assertEquals(200, $result);
    }

    public function _testBroadcastAndroid() {
        $message = 'broadcasting to android';

        try {
            $result = $this->airship->broadcast_android($message);
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals(200, $result);
    }

    public function _testAddDeviceTag() {
        try {
            $result = $this->airship->add_device_tag('jonas', $this->device_token, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(true, $result);
    }

    public function _testPushToTag() {
        try {
            $result = $this->airship->push_to_tags('Hello unit testing3', array('jonas'), 'ios');
            error_log("test push:" . $result);
            
        } catch (Exception $e) {
            error_log($e->getMessage);
        }

        $this->assertEquals(true, $result);
    }

    public function _testDeleteDeviceToken() {

        try {
            $result = $this->airship->delete_device_tag('in', $this->device_token, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(true, $result);
    }

    public function _testDeleteAlreadyDeletedTag() {

        try {
            $result = $this->airship->delete_device_tag('in', $this->device_token, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(false, $result);
    }

}

?>
