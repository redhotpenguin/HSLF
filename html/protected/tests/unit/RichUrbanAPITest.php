<?php

Yii::import('application.vendors.*');
require_once('urbanairship/rich_urbanairship.php');
require_once('urbanairship/urbanairship.php');

class RichUrbanAPITest extends CTestCase {

    private $airship;
    private $rich_airship;
    private $api_key = 'ouRCLPaBRRasv4K1AIw-xA';
    private $api_secret = '7hd19C6rSzyrbKM3k6KqDg';
    
    private $device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';

    public function __construct() {
        $this->airship = new Airship($this->api_key, $this->api_secret);
        $this->rich_airship = new Rich_Airship($this->api_key, $this->api_secret);
    }



 

    public function testAddDeviceTag() {
        try {
            $user_id = 'UwsN1BVESquaXdLA56QzSA';
            $result = $this->rich_airship->update_device_tags(array( 'tag1', 'tag4'), $this->device_token, $user_id,'ios', 'rich_alias');
            error_log("test result:" . $result);    
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(true, $result);
    }
    
    public function testRegistration(){
        
           // $result = $this->airship->register_ios($this->device_token,  'my_alias');
        
        $result = $this->rich_airship->update_alias_tags($this->device_token, 'rich_alias', array('tag'));
          $this->assertEquals(true, $result);
        
        
        // $this->assertEquals(true, $result);
    }
    
    public function _testGetUsers(){
        
        $result = $this->rich_airship->getUsers();
    }

 

 

 
}

?>
