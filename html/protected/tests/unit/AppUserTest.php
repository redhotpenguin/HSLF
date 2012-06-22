<?php

Yii::import('application.config.config.php');
Yii::import('application.shared.models.dal.*');
Yii::import('application.shared.models.bll.*');
Yii::import('application.api.models.*');

class AppUserTest extends CDbTestCase {

    private $device_token;
    private $uap_user_id;
    private $type = 'android';
    private $district_id;
    private $optional;
    private $meta;
    private $new_tags;
    private $app_user;

    public function __construct() {
        $this->device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';
        $this->uap_user_id = 'UwsN1BVESquaXdLA56QzSA';
        $this->district_id = DistrictManager::getDistrictId('ca', 'congressional', '1');
        $this->optional = array(
            'user_agent' => 'firefox',
        );
        
        
   

        $this->meta = array(
            'name' => 'jonas palmero',
            'email' => 'jonas@wmse.com'
        );

        $this->new_tags = array('tag1', 'tag2');
        
        $this->optional = array(
            'meta' => $this->meta,
            'tags' => $this->new_tags,
        );
        

        $this->app_user = Application_user::model()->findByAttributes(array('device_token' => $this->device_token));
       

               

    }
 
    
    
    public function _testAddApplicationUser() {


        $add_user =  ApplicationUserManager::addApplicationUser($this->device_token, $this->uap_user_id, $this->type, $this->district_id, $this->optional);

        $this->assertTrue($add_user);
    }

    public function testUpdateApplicationUserMetaByDeviceToken() {
    

        $update_meta = $this->app_user->updateUserMeta($this->meta);

        $this->assertTrue($update_meta);
    }

    public function _testUpdateApplicationUserTagsByDeviceToken() {
 

        $update_meta =  ApplicationUserManager::updateApplicationUserTagsByDeviceToken($this->device_token, $this->new_tags);

        $this->assertTrue($update_meta);
    }

}

?>