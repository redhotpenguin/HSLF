<?php

/**
 * Unitary tests for MobileUser
 *
 * @author Jonas
 */
class MobileUserTest extends CDbTestCase {

    private $newName = "Jonas Antoine Etienne Palmero";
    private $tenantBehavior;

    public function __construct() {
        $this->tenantBehavior = new MultiTenantBehavior();
    }

    private function getMobileUser() {
        $mUser = new MobileUser();
        $mUser->attachBehavior('MultiTenant', $this->tenantBehavior);
        return $mUser;
    }

    public function testSave() {
        $mUser = $this->getMobileUser();
        $mUser->sessionTenantId = 13;
        $saveResult = $mUser->save();
        $this->assertTrue($saveResult);
    }

    public function testUpdate() {
        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = 2;
        $mobileUser->name = "jonas palmero";

        $this->assertTrue($mobileUser->save());

        $mobileUser2 = $this->getMobileUser();
        $mobileUser2->sessionTenantId = 2;

        $test = $mobileUser2->findByAttributes(array("name" => $mobileUser->name));

        $this->assertNotNull($test);

        $test->name = $this->newName;

        $updateResult = $test->update();

        $this->assertTrue($updateResult);

        $this->assertEquals($this->newName, $test->name);
    }

    public function testDelete() {

        $mobileUser =  $this->getMobileUser();
        $mobileUser->sessionTenantId = 14;

        $this->assertTrue($mobileUser->save());

        $oid = $mobileUser->_oid;

        $deleteResult = $mobileUser->delete();

        $this->assertTrue($deleteResult);

        $t =  $this->getMobileUser();
        $t->sessionTenantId = 14;
        $deletedUser = $t->findByPk($oid);

        $this->assertNull($deletedUser);
    }

    // designed to also check tenant compliancy
    public function testFindAllByAttributes() {
        $tenantId = 15;

        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = $tenantId;
        $mobileUser->interests = "salad";
        $this->assertTrue($mobileUser->save());

        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = $tenantId;
        $mobileUser->interests = "salad";
        $this->assertTrue($mobileUser->save());

        $test = $this->getMobileUser();
        $test->sessionTenantId = 15;

        $mobileUsers = $test->findAllByAttributes(array("interests" => "salad"));

        $this->assertNotEmpty($mobileUsers);

        $test = $this->getMobileUser();
        $test->sessionTenantId = 1;
        $mobileUsers = $test->findAllByAttributes(array("interests" => "salad"));

        $this->assertEmpty($mobileUsers);
    }

    // designed to also check tenant compliancy
    public function testFindByAttributes() {
        $email = "jonas.palmero@gmail.com";
        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = 17;
        $mobileUser->email = $email;
        $this->assertTrue($mobileUser->save());

        $mobileUser2 = $this->getMobileUser();
        $mobileUser2->sessionTenantId = 18;
        $result = $mobileUser2->findByAttributes(array("email" => $email));

        $this->assertNull($result);
        $this->assertNotEmpty($mobileUser->findByAttributes(array("email" => $email)));
    }


    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

}

?>
