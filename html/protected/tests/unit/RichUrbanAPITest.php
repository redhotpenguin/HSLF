<?php

Yii::import('application.vendors.*');
require_once('urbanairship/rich_urbanairship.php');
require_once('urbanairship/urbanairship.php');

class RichUrbanAPITest extends CTestCase {

    private $airship;
    private $rich_airship;
    private $api_key = 'ouRCLPaBRRasv4K1AIw-xA';
    private $api_secret = '7hd19C6rSzyrbKM3k6KqDg';
    private $user_id = 'UwsN1BVESquaXdLA56QzSA';
    private $device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';

    public function __construct() {
        $this->airship = new Airship($this->api_key, $this->api_secret);
        $this->rich_airship = new Rich_Airship($this->api_key, $this->api_secret);
    }

    public function _testAddDeviceTag() {
        try {
            $user_id = 'UwsN1BVESquaXdLA56QzSA';
            $result = $this->rich_airship->update_device_tags(array(''), $this->device_token, $user_id, 'ios');
            error_log("test result:" . $result);
        } catch (Exception $e) {
            error_log($e->getMessage);
        }


        $this->assertEquals(true, $result);
    }

    public function _testGetUsers() {

        $result = $this->rich_airship->getUsers();
    }

    public function testSendRishPush() {
        $alert = 'A new alert for you sir';

        $airmail_payload = array(
            'title' => 'Sanity',
            'message' => 'Check'
        );

        $users = array(
            $this->user_id
        );


        $audience = array(
           //'tags' => array('TheDude')
            'users' => array($this->user_id),
        );
        

        try{
            $result = $this->rich_airship->sendRichNotification($audience,  $airmail_payload, $alert);
        }
        
        catch(Exception $e){
            error_log($e->getMessage());
            error_log(print_r($result, true));
        }
        
        
        
        $this->assertTrue($result);
    }

}

?>
