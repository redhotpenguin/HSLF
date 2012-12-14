<?php

/**
 * Unitary tests for MobileUser
 *
 * @author jonas
 */
class MobileUserTest extends CDbTestCase {

    private $mobileUser;
    private $newName;

    public function __construct() {
        $this->mobileUser = new MobileUser();
        $this->mobileUser->tenant_id = 1;
        $this->mobileUser->name = "Unit tests";
        $this->newName = "Jonas Antoine Etienne Palmero";
    }

    public function testSave() {
        $this->log($this->mobileUser->fields);
        $saveResult = $this->mobileUser->save(1);

        $this->log($this->mobileUser->lastError);

        $this->assertTrue($saveResult);
    }

    public function testUpdate() {
        $mobileUser = new MobileUser();
        $mobileUser->tenant_id = 2;
        $mobileUser->name = "jonas palmero";


        $this->assertTrue($mobileUser->save());

        $mobileUser2 = MobileUser::model()->findByAttributes(array("name" => $mobileUser->name));

        $this->assertNotNull($mobileUser2);

        $mobileUser2->name = $this->newName;

        $updateResult = $mobileUser2->update();

        $this->assertTrue($updateResult);

        $this->assertEquals($this->newName, $mobileUser2->name);
    }

    public function _testDelete() {
        $mobileUser = MobileUser::model()->findByAttributes(array("name" => $this->newName));

        $this->assertNotNull($mobileUser);

        $deleteResult = $mobileUser->delete();

        $this->assertTrue($deleteResult);

        $deletedUser = MobileUser::model()->findByAttributes(array("name" => $this->newName));

        $this->assertNull($deletedUser);
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

}

?>
