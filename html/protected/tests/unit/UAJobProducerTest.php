<?php

class UAJobProducerTest extends CDbTestCase {

    private $uaJobProducer;
    private $tenant;

    public function __construct() {

        $this->tenant = Tenant::model()->findByPk(1);

        $this->uaJobProducer = new UAJobProducer($this->tenant);
    }

    public function testModel() {
        $this->assertNotNull($this->uaJobProducer);
    }
    
    public function testPushUrbanAirshipMessage(){
        $result = false;
        
        $alert = "unit tests!!";
        $searchAttributes = array(
            'tenant_id' => 1
        );
        $extra = array();
        
        $result = $this->uaJobProducer->pushUrbanAirshipMessage($alert, $searchAttributes, $extra);
        
        $this->assertTrue($result);
    }

}
