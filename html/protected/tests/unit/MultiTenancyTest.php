<?php

class MultiTenanctTest extends CDbTestCase {

    private $tenantBehavior;

    public function __construct() {
        $this->tenantBehavior = new MultiTenantBehavior();
    }

    private function getOptionModel() {
        $option = new Option();
        $option->attachBehavior('MultiTenant', $this->tenantBehavior);
        $option->name = "unit_test_" . md5(microtime());
        $option->value = "a_value";
        return $option;
    }

    private function getAlertTypeModel($tagTenantId) {


        $alertType = new AlertType();

        $alertType->attachBehavior('MultiTenant', $this->tenantBehavior);

        $tag = new Tag();
        $tag->attachBehavior('MultiTenant', $this->tenantBehavior);

        $tag = $tag->findByAttributes(array('tenant_id' => $tagTenantId, 'name' => 'my_tag'));


        if ($tag == null) {
            $tag = new Tag();
            $tag->name = 'my_tag';
            $tag->type = 'alerts';
            $tag->sessionTenantId = $tagTenantId;
            $tag->save();
        }
        $tagId = $tag->id;


        $alertType->attachBehavior('MultiTenant', $this->tenantBehavior);
        $alertType->display_name = "unit_test_" . md5(microtime());
        $alertType->tag_id = $tagId;
        $alertType->category = "unitary_test";
        return $alertType;
    }

    /**
     * direct tenancy = model with a tenant_id attribute
     */
    public function testDirectTenancy() {

        // save a direct tenant model
        $option = $this->getOptionModel();

        $option->sessionTenantId = 1;

        $result = $option->save();

        $id = $option->id;

        $this->assertTrue($result);

        // test that a tenant can retrieve models with the same tenant_id
        $option = null;

        $option = $this->getOptionModel();

        $option->sessionTenantId = 1;

        $option = $option->findByPk($id);

        $this->assertEquals($id, $option->id);

        // test that a tenant cannot retrieve another tenant's model
        $option = null;

        $option = $this->getOptionModel();

        $option->sessionTenantId = 2;

        $option = $option->findByPk($id);

        $this->assertNull($option);
    }

    /**
     * indirect tenancy = model with no tenant_id attribute. Ex: alert_type
     */
    public function testIndirectTenancy() {

        $alertType = $this->getAlertTypeModel(1);
        $alertType->sessionTenantId = 1;

        $result = $alertType->save();

        $this->assertTrue($result);
    }

    public function testIndirectTenancy2() {
        // test that a tenant can't save a model for another tenant
        $alertType = $this->getAlertTypeModel(1);
        $alertType->display_name = $alertType->display_name . 'hacked';
        $alertType->sessionTenantId = 2;


        // an exception happens when there is a tenant id mismatch
        try {
            $result = $alertType->save();
        } catch (Exception $e) {
            $result = null;
        }

        $this->assertNull($result);
    }

    public function testIndirectTenancy3() {
        // test that a tenant can't save a model for another tenant
        $alertType = new AlertType();
        $alertType->attachBehavior('MultiTenant', $this->tenantBehavior);
        $alertType->sessionTenantId = 2;
        $alertType->tag_id = 10;
        $alertType->display_name = "you suck";
        $alertType->category = "tests";


        // an exception happens when there is a tenant id mismatch
        try {
            $result = $alertType->save();
        } catch (Exception $e) {
            $result = null;
        }

        $this->assertNull($result);
    }

}