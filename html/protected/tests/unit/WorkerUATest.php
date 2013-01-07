<?php

require('/var/www/html/mobile_platform/worker/WorkerLibrary/UrbanAirship.php');

use WorkerLibrary\UrbanAirship as UrbanAirship;

class WorkerUATest extends CDbTestCase {

    public function testFoo() {
        $this->assertTrue(true);
        new UrbanAirship();
    }

}
