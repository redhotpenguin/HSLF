<?php

require('/var/www/html/mobile_platform/worker/WorkerLibrary/UrbanAirship.php');

use WorkerLibrary\UrbanAirship as UrbanAirship;

class WorkerUATest extends CDbTestCase {

    private $airship;

    public function __construct() {
       
        $this->airship = new UrbanAirship("G3QQPQEERdKchSqDPq6Gag", "FT98LRhLRNOPHBg8k-5iyg");
    }
    
    // directly use the uap library
    public function testSendPushNotification() {
        $apids = array(
            "5d1cf0cb-90ce-4960-8c12-adc8c8bec35e"
        );

        $result = $this->airship->sendPushNotification("hello jonas!!", null, $apids);

        $this->assertNotEquals(false, $result);

    }

}
