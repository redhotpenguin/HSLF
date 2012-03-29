<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class UrbanAPITest extends CTestCase {

    private $airship;
    private $api_key = 'ouRCLPaBRRasv4K1AIw-xA';
    private $api_secret = '7hd19C6rSzyrbKM3k6KqDg';

    public function __construct() {
        $this->airship = new Airship($this->api_key, $this->api_secret);
    }

    public function testPushIOS() {


        $device_tokens = array('0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC');
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

    public function testPushAndroid() {
        $droid_token = array('fec7e94a-c9b6-4874-a5b3-c1626cd70355');

        $tags = array('tag1', 'tag2');
        $alias = array('alias');

        $alert = 'hello world';

        try {
            $result = $this->airship->push_android($alert, $droid_token, $tags, $alias, 'extra!');
        } catch (Exception $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals(200, $result);
    }

}

?>
