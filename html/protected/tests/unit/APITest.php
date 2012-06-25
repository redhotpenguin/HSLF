<?php

Yii::import('application.config.config.php');
Yii::import('application.shared.models.dal.*');
Yii::import('application.shared.models.bll.*');
Yii::import('application.api.models.*');

class APITest extends CDbTestCase {

    private $device_token;
    private $uap_user_id;
    private $type = 'ios';
    private $district_id;
    private $optional;
    private $meta;
    private $new_tags;
    private $geolocation = array(
        'lat' => '37,42291810',
        'long' => '-122,08542120'
    );

    public function __construct() {



        $this->device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';
        $this->uap_user_id = 'UwsN1BVESquaXdLA56QzSA';
        $this->district_id = DistrictManager::getDistrictId('ca', 'congressional', '1');


        $this->meta = array(
            'name' => 'jonas palmero',
            'email' => 'jonas@wmse.com'
        );

        $this->new_tags = array('tag1', 'tag2');

        $this->optional = array(
            'user_agent' => 'firefox',
            'meta' => $this->meta,
            'tags' => $this->new_tags,
            'geolocation' => $this->geolocation,
        );


        // delete test user
        $user = Application_user::model()->findByAttributes(array('device_token' => $this->device_token));
        if ($user)
            $user->delete();
    }

    public function testGetDistrictIDCreateIfNotExist() {

        $api = new RestAPI();

        $district_id = $api->getDistrictIDCreateIfNotExist('ak', 'congressional', 61);


        $this->assertNotEquals(false, $district_id);
    }

    public function testRegisterApplicationUser() {
        $api = new RestAPI();

        $register_user = $api->registerApplicationUser($this->device_token, $this->uap_user_id, $this->type, 'ca', 'congressional', '41', $this->optional);

        $this->assertEquals('insert_ok',$register_user);
    }

    public function _testUpdateApplicationUserMetaByDeviceToken() {
        $api = new API();

        $update_meta = $api->updateApplicationUserMetaByDeviceToken($this->device_token, $this->meta);

        $this->assertTrue($update_meta);
    }

    public function _testUpdateApplicationUserTagsByDeviceToken() {
        $api = new API();

        $update_meta = $api->updateApplicationUserTagsByDeviceToken($this->device_token, $this->new_tags);

        $this->assertTrue($update_meta);
    }

}

?>