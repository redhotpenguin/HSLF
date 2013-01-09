<?php

require('/var/www/html/mobile_platform/worker/WorkerLibrary/UrbanAirship.php');

use WorkerLibrary\UrbanAirship as UrbanAirship;

class WorkerUATest extends CDbTestCase {

    private $airship;

    public function __construct() {

        //$this->airship = new UrbanAirship("G3QQPQEERdKchSqDPq6Gag", "FT98LRhLRNOPHBg8k-5iyg"); // our oregon dev
        
        
        $this->airship = new UrbanAirship("3ZdPxcFfSda0rpWtlwE68w", "42YO18MlSBC6JC-ewFoK2w"); // alloy dev
    }

    // directly use the uap library
    public function testSendPushNotification() {
        $apids = array(
            "5d1cf0cb-90ce-4960-8c12-adc8c8bec35e"
        );

        $tokens = array( "120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232" ) ; // dev iphone

        $result = $this->airship->sendPushNotification("Bonjour Jonas!!", $tokens, null);

        $this->assertTrue($result);
    }

}
