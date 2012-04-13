<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class UrbanAPITest extends CTestCase {

    private $airship;
    private $api_key = 'ouRCLPaBRRasv4K1AIw-xA';
    private $api_secret = '7hd19C6rSzyrbKM3k6KqDg';
    private $device_token = '0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC';

    public function __construct() {
        $this->airship = new Airship($this->api_key, $this->api_secret);
    }

    /*

      public function testPushIOS() {


      $device_tokens = array('1374BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');
      $alert = 'Hello WOrld';
      $payload = array('aps' => array('alert' => $alert));
      $result = '';

      try {
      $result = $this->airship->push_ios($payload, $device_tokens);
      } catch (Exception $e) {
      $result = $e->getMessage();
      }

      $this->assertEquals(200, $result);
      }
     */

    /*
      public function testPushAndroid() {
      $droid_token = array('cf2b078f-3c8f-40fe-a165-d28ea7e0d062');



      $alert = 'hello from unit testing';

      try {
      $result = $this->airship->push_android($alert, $droid_token);
      } catch (Exception $e) {
      $result = $e->getMessage();
      }

      $this->assertEquals(200, $result);
      }
     */

    /*
      public function testBroadcastIos() {
      $message = 'Did you get that broadcast message?';
      $broadcast_message = array(
      'aps' => array('alert' => $message )
      );

      try{
      $result = $this->airship->broadcast_ios($broadcast_message);
      }

      catch(Exception $e){
      $result = $e->getMessage();
      }

      error_log($result);

      $this->assertEquals(200, $result);
      }
     */
    /*
      public function testBroadcastAndroid() {
      $message = 'broadcasting to android';

      try {
      $result = $this->airship->broadcast_android($message);
      } catch (Exception $e) {
      $result = $e->getMessage();
      }

      $this->assertEquals(200, $result);
      }
     * */

    public function testDeleteDeviceToken() {

        try {
            $result = $this->airship->delete_device_tag('ri_2', $this->device_token, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(true, $result);
    }

    public function testDeleteAlreadyDeletedTag() {

        try {
            $result = $this->airship->delete_device_tag('az_4', $this->device_token, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(false, $result);
    }

    public function _testCode() {
        $this->assertEquals(true, $this->airship->_validate_http_code(200));
        $this->assertEquals(true, $this->airship->_validate_http_code(201));
        $this->assertEquals(true, $this->airship->_validate_http_code(201));
        $this->assertEquals(false, $this->airship->_validate_http_code(404));
        $this->assertEquals(false, $this->airship->_validate_http_code(500));
    }

}

?>
