<?php

class UrbanAirshipNotifierTest extends CTestCase {

    private $notifier;
      private $device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';

    public function __construct() {
        $this->notifier = new UrbanAirshipNotifier();
    }


 

    public function _testSendPushNotifications(){
        $test_result = $this->notifier->sendPushNotifications('alert notifier', array('il'));
        $this->assertEquals(true, $test_result);
    }

    
    
    
    public function  _testAddDeviceTag(){
        $test_result = $this->notifier->add_device_tag('il', $this->device_token, 'ios');
        $this->assertEquals(true, $test_result);
    }
    
    public function _testDeleteDeviceTag(){
        $test_result = $this->notifier->delete_device_tag('il', $this->device_token, 'ios');
        $this->assertEquals(true, $test_result);
    }
    
    
     public function  _testAddWrongDeviceTag(){
        $test_result = $this->notifier->add_device_tag('hello', $this->device_token.'abc', 'ios');
        $this->assertEquals(false, $test_result);
    }
    
    public function testUpdateTags(){
        
        $tags = array('victory','shall','be', 'mine');
        
        $test_result = $this->notifier->updateRichUserTags('UwsN1BVESquaXdLA56QzSA', '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232', $tags);
       
        $this->assertEquals(true, $test_result);

        
    }

}

?>
