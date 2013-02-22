<?php

class MultiTenanctTest extends CDbTestCase {

    private $tenantBehavior;

    public function __construct() {
        $this->tenantBehavior = new MultiTenantBehavior();
    }

    private function getOptionModel() {
        $option = new Option();
        $option->name = "unit_test_" . md5(microtime());
        $option->value = "a_value";
        return $option;
    }

    private function getAlertTypeModel($tenantId) {

        $alertType = new AlertType();

        $tag = new Tag();
        $tag->attachBehavior('MultiTenant', $this->tenantBehavior);

        $tag = $tag->findByAttributes(array('tenant_id' => $tenantId, 'name' => 'my_tag'));


        if ($tag == null) {
            $tag = new Tag();
            $tag->attachBehavior('MultiTenant', $this->tenantBehavior);
            Yii::app()->params['current_tenant_id'] = $tenantId;
            $tag->name = 'my_tag';
            $tag->display_name = "unit test tag";
            $tag->type = 'alerts';
            $r = $tag->save();
        }
        $tagId = $tag->id;


        $alertType->attachBehavior('MultiTenant', $this->tenantBehavior);
        $alertType->display_name = "unit_test_" . md5(microtime());
        $alertType->tag_id = $tagId;
        $alertType->category = "unitary_test";
        return $alertType;
    }

    public function testDirectTenancy() {

        // save a direct tenant model
        $option = $this->getOptionModel();

        Yii::app()->params['current_tenant_id'] = 1;

        $result = $option->save();

        $id = $option->id;

        $this->assertTrue($result);

        // test that a tenant can retrieve models with the same tenant_id
        $option = null;

        $option = $this->getOptionModel();

        Yii::app()->params['current_tenant_id'] = 1;

        $option = $option->findByPk($id);

        $this->assertEquals($id, $option->id);

        // test that a tenant cannot retrieve another tenant's model
        $option = null;

        $option = $this->getOptionModel();

        Yii::app()->params['current_tenant_id'] = 2;

        try {

            $option = $option->findByPk($id);
        } catch (Exception $e) {
            error_log("yo dog");
            error_log($e->getMessage());
        }

        $this->assertNull($option);
    }

    public function testIndirectTenancy() {
        Yii::app()->params['current_tenant_id'] = 1;

        $alertType = $this->getAlertTypeModel(1);

        $result = $alertType->save();

        $this->assertTrue($result);
    }

    public function testIndirectTenancy2() {


        // test that a tenant can't save a model for another tenant
        $alertType = $this->getAlertTypeModel(1);



        Yii::app()->params['current_tenant_id'] = 2;


        $alertType->display_name = $alertType->display_name . 'hacked';


        // an exception happens when there is a tenant id mismatch
        try {
            $result = $alertType->save();
        } catch (Exception $e) {
            $result = null;
        }

        $this->assertNull($result);
    }

    public function testIndirectTenancy3() {
        $alertType = new AlertType();

        $attributes = array(
            'display_name' => 'hack',
            'category' => 'foo',
            'tag_id' => 10
        );

        $alertType->attributes = $attributes;
        Yii::app()->params['current_tenant_id'] = 2;

        // an exception happens when there is a tenant id mismatch
        try {
            $result = $alertType->save();
        } catch (Exception $e) {
            $result = null;
            error_log($e->getMessage());
        }

        $this->assertNull($result);
    }

    public function testIndirectTenancyUpdate() {
// scenario: tenant A has a Tag
// another tenant B tries to use this tag by altering the ID during an UPDATE
// simulate other tenant session
        Yii::app()->params['current_tenant_id'] = 2;

        $alertType2 = $this->getAlertTypeModel(2);

        $alertType2->save();

        $alertType2->tag_id = 9; // 9 = tag id from tenant A
        $alertType2->display_name = $alertType2->display_name . ' hacked';


        // simulate attack
        try {
            $result = $alertType2->save();
        } catch (Exception $e) {
            $result = null;
            error_log($e->getMessage());
        }

        $this->assertNull($result);
    }

}