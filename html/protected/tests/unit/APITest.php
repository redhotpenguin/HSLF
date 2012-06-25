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
    private $api;

    public function __construct() {
        $this->api = new RestAPI();

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


        $district_id = $this->api->getDistrictIDCreateIfNotExist('ak', 'congressional', 61);


        $this->assertNotEquals(false, $district_id);
    }

    public function testRegisterApplicationUser() {


        $register_user = $this->api->registerApplicationUser($this->device_token, $this->uap_user_id, $this->type, 'ca', 'congressional', '41', $this->optional);

        $this->assertEquals('insert_ok', $register_user);
    }

    public function testUpdateApplicationUserTag() {
        
        $tags = array(
            'add_tags' => array('tag2', 'tag3', 'tag1'),
            'delete_tags' => array('tag3')
        );
        
        $update_user_tags = $this->api->updateApplicationUserTags($this->device_token, $tags, 436 );
    
        $this->assertEquals('update_tags_ok', $update_user_tags);
    }

}

?>