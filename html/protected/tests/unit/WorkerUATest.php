<?php

require('/var/www/html/mobile_platform/worker/WorkerLibrary/UrbanAirship.php');

use WorkerLibrary\UrbanAirship as UrbanAirship;

class WorkerUATest extends CDbTestCase {

    private $airship;

    public function __construct() {

       // $this->airship = new UrbanAirship("G3QQPQEERdKchSqDPq6Gag", "FT98LRhLRNOPHBg8k-5iyg"); // our oregon dev
        
        
       $this->airship = new UrbanAirship("3ZdPxcFfSda0rpWtlwE68w", "42YO18MlSBC6JC-ewFoK2w"); // alloy dev
    }

    // directly use the uap library
    public function testSendPushNotification() {
        $apids = array(
            "9fffae32-b3f5-4836-9078-e42e9f34f830"
        );

        $tokens = array( 
            
            "120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232", // jonas
          //  "0974BC876666E2BF7400BC8FED62D3FAE1B249E0702974B16C00FC62495AA9CC" // jeremy
            
            
            ) ; // dev iphone

        $data = array(
            'type' => 'post',
                'id' => "2456",
         
            
        );
        
        $result = $this->airship->sendPushNotification("Unit Tests", $tokens, $apids, $data);

        $this->assertTrue($result);
    }

}
