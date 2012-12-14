<?php

/**
 * Unitary tests for MobileUser
 *
 * @author Jonas
 */
class MobileUserTest extends CDbTestCase {

    private $mobileUser;
    private $newName;
    private $tenantBehavior;

    public function __construct() {
        $this->mobileUser = new MobileUser();
        $this->mobileUser->sessionTenantId = 1;
        $this->tenantBehavior = new MultiTenantBehavior();
        $this->mobileUser->attachBehavior('MultiTenant', $this->tenantBehavior);

        $this->mobileUser->name = "Unit tests";
        $this->newName = "Jonas Antoine Etienne Palmero";
    }

    public function testSave() {
        $this->log($this->mobileUser->fields);
        $saveResult = $this->mobileUser->save(1);
        $this->assertTrue($saveResult);
    }

    public function testUpdate() {
        $mobileUser = new MobileUser();
        $mobileUser->tenant_id = 2;
        $mobileUser->name = "jonas palmero";

        $this->assertTrue($mobileUser->save());

        $mobileUser2 = MobileUser::model();
        $mobileUser2->sessionTenantId = 2;

        $test = $mobileUser2->findByAttributes(array("name" => $mobileUser->name));

        $this->assertNotNull($test);

        $test->name = $this->newName;

        $updateResult = $test->update();

        $this->assertTrue($updateResult);

        $this->assertEquals($this->newName, $test->name);
    }

    public function testDelete() {

        $mobileUser = new MobileUser();
        $mobileUser->tenant_id = 14;

        $this->assertTrue($mobileUser->save());

        $oid = $mobileUser->_oid;

        $deleteResult = $mobileUser->delete();

        $this->assertTrue($deleteResult);

        $deletedUser = MobileUser::model()->findByPk($oid);

        $this->assertNull($deletedUser);
    }

    // designed to also check tenant compliancy
    public function testFindAllByAttributes() {
        $tenantId = 15;

        $mobileUser = new MobileUser();
        $mobileUser->sessionTenantId = $tenantId;
        $mobileUser->interests = "salad";
        $this->assertTrue($mobileUser->save());

        $mobileUser = new MobileUser();
        $mobileUser->tenant_id = $tenantId;
        $mobileUser->interests = "salad";
        $this->assertTrue($mobileUser->save());

        $test = MobileUser::model();
        $test->sessionTenantId = 15;

        $mobileUsers = $test->findAllByAttributes(array("interests" => "salad"));

        //$this->log("mobile users:");
        // $this->log($mobileUsers);

        $this->assertNotEmpty($mobileUsers);

        $test = MobileUser::model();
        $test->sessionTenantId = 1;
        $mobileUsers = $test->findAllByAttributes(array("interests" => "salad"));

        $this->assertEmpty($mobileUsers);
    }

    // designed to also check tenant compliancy
    public function testFindByAttributes() {
        $email = "jonas.palmero@gmail.com";
        $mobileUser = new MobileUser();
        $mobileUser->tenant_id = 17;
        $mobileUser->sessionTenantId = 17;
        $mobileUser->email = $email;
        $this->assertTrue($mobileUser->save());

        $mobileUser2 = new MobileUser();
        $mobileUser2->sessionTenantId = 18;
        $result = $mobileUser2->findByAttributes(array("email" => $email));


        $this->assertNull($result);


        $this->assertNotEmpty($mobileUser->findByAttributes(array("email" => $email)));
    }

    public function testfindAllByAttributesTenancy() {
        $mobileUsers = $this->mobileUser->findAllByAttributes(array("name" => "Jonas Antoine Etienne Palmero"));
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

}

?>
