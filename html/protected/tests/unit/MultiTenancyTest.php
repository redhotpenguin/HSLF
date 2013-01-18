<?php

class MultiTenanctTest extends CDbTestCase {

    private $tenantBehavior;

    public function __construct() {
        $this->tenantBehavior = new MultiTenantBehavior();
    }

    private function getOptionModel() {
        $option = new Option();
        $option->attachBehavior('MultiTenant', $this->tenantBehavior);
        $option->name = "an_option";
        $option->value = "a_value";
        return $option;
    }

    public function testSave() {
        $option = $this->getOptionModel();
        
        $option->sessionTenantId = 1;
        
        $result =    $option->save();
        
        $id = $option->id;
        
        $this->assertTrue($result);
    }
    
}