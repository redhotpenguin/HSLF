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
        $mUser->device_type = "android";
        return $mUser;
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

    public function testSave() {
        $mUser = $this->getMobileUser();
        $mUser->name = "testSave";
        $mUser->sessionTenantId = 13;
        $saveResult = $mUser->save();
        if ($saveResult == false) {
            $this->log("testSaveError:");
            $this->log($mUser->lastError);
        }
        $this->assertTrue($saveResult);
    }

    public function testUpdate() {

        // add a new mobile user
        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = 2;
        $mobileUser->name = "jonas palmero";
        $this->assertTrue($mobileUser->save());

        //
        $mobileUser2 = $this->getMobileUser();
        $mobileUser2->sessionTenantId = 2;

        error_log("updated $mobileUser->_id");


        // get another instance of MobileUser
        $test = $mobileUser2->findByAttributes(array("name" => $mobileUser->name));

        $this->assertNotNull($test);

        $test->name = $this->newName;

        $updateResult = $test->update();

        $this->assertTrue($updateResult);

        $this->assertEquals($this->newName, $test->name);
    }

    public function testDelete() {

        $mobileUser = $this->getMobileUser();
        $mobileUser->sessionTenantId = 14;

        $this->assertTrue($mobileUser->save());

        $oid = $mobileUser->_oid;

        $deleteResult = $mobileUser->delete();

        $this->assertTrue($deleteResult);

        $t = $this->getMobileUser();
        $t->sessionTenantId = 14;
        $deletedUser = $t->findByPk($oid);

        $this->assertNull($deletedUser);
    }

    public function testUpdatePushSet() {
        $result = false;

        $mUser = $this->getMobileUser();

        $mUser->sessionTenantId = 3;
        $mUser->tags = array("tag1", "tag2");
        $this->assertTrue($mUser->save());
        $oid = $mUser->_id;


        $set = array(
            "name" => "new name",
            "age" => 14
        );


        $push = array(
            "tags" => "tag3"
        );

        $deviceIdentifier = $mUser->device_identifier;

        $conditions = array(
            "tenant_id" => 3,
            "device_identifier" => $deviceIdentifier
        );

        $result = $mUser->update($conditions, $set, $push, 1);


        $this->log($mUser->fields);

        $this->assertTrue($result);
    }

    // designed to check multi tenant compliancy
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
        $mobileUsers = $test->findAllByAttributes(array("interests" => "salad", "tenant_id" => 15));


        $this->assertEmpty($mobileUsers);
    }

    public function testRules() {

        $mUser = $this->getMobileUser();

        $mUser->sessionTenantId = 1;
        $mUser->device_type = null;
        $result = $mUser->save();

        $this->assertFalse($result);


        $mUser = $this->getMobileUser();

        $mUser->sessionTenantId = 1;
        $mUser->device_type = "android";

        $result = $mUser->save();

        $this->assertTrue($result);
    }

    public function testNoDupesInArrays() {


        $mUser = $this->getMobileUser();
        $mUser->sessionTenantId = 1;
        $mUser->events = array("event1");
        $result = $mUser->save();

        $identifier = $mUser->device_identifier;
        $this->assertTrue($result);


        $mUser = new MobileUser();
        $mUser->sessionTenantId = 1;

        $conditions = array(
            "tenant_id" => 1,
            "device_identifier" => $identifier
        );

        $set = array(
        );

        $push = array(
            'events' => "event1"
        );

        $updateResult = $mUser->update($conditions, $set, $push);

        $this->assertTrue($updateResult);


        $mUser = MobileUser::model()->findByAttributes(
                array(
                    "tenant_id" => 1,
                    "device_identifier" => $identifier
                )
        );

        $this->assertNotEmpty($mUser);

        $this->assertEquals(1, count($mUser->events));
    }

    public function testApidValidationFormat() {
        $mobileUser = $this->getMobileUser();
        $mobileUser->device_type = 'android';
        $mobileUser->ua_identifier = "9fffae32-b3f5-4836-9078-e42e9f34f830";
        $validationResult = $mobileUser->validate('ua_identifier');
        $this->assertEmpty($mobileUser->getError('ua_identifier'));
        $this->assertTrue($validationResult);
    }

    public function testInvalidApidValidationFormat() {
        $mobileUser = $this->getMobileUser();
        $mobileUser->device_type = 'android';
        $mobileUser->ua_identifier = "19fffae32-b3f5-4836-9078-e42e9f34f830";
        $validationResult = $mobileUser->validate('ua_identifier');
        $this->assertNotEmpty($mobileUser->getError('ua_identifier'));
        $this->assertFalse($validationResult);
    }

    public function testTokenValidationFormat() {
        $mobileUser = $this->getMobileUser();
        $mobileUser->device_type = 'ios';
        $mobileUser->ua_identifier = "120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232";
        $validationResult = $mobileUser->validate('ua_identifier');
        $this->assertEmpty($mobileUser->getError('ua_identifier'));
        $this->assertTrue($validationResult);
    }
    
    public function testInvalidTokenValidationFormat() {
        $mobileUser = $this->getMobileUser();
        $mobileUser->device_type = 'ios';
        $mobileUser->ua_identifier = "1120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232";
        $validationResult = $mobileUser->validate('ua_identifier');
        $this->assertNotEmpty($mobileUser->getError('ua_identifier'));
        $this->assertFalse($validationResult);
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

    private function dump($x) {
        ob_start();
        var_dump($x);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

?>
