<?php

class OptionTest extends CDbTestCase {

    private $option;
    private $optionBehavior;
    private $tenantBehavior;

    public function __construct() {
        $this->option = new Option();
        $this->optionBehavior = new OptionBehavior();
        $this->tenantBehavior = new MultiTenantBehavior();

        $this->option->attachBehavior('Behavior', $this->optionBehavior);
        $this->option->attachBehavior('MultiTenant', $this->tenantBehavior);

        Yii::app()->params['current_tenant_id']  = 1;
    }

    public function testUpsert() {

        $result = $this->option->upsert("foo", "bar");
        $result = $this->option->upsert("foo", "bar41");

        error_log("result: $result");

        $this->assertTrue($result);
    }

}

?>
