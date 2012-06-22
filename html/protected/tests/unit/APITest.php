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
        
        public function __construct(){
            $this->device_token = '120231606E4C8C45F50DA3D0CFB59D78CBE22E0192F63E5A08401BC3BA610232';
            $this->uap_user_id = 'UwsN1BVESquaXdLA56QzSA';
            $this->district_id = DistrictManager::getDistrictId('ca', 'congressional', '1');
            $this->optional = array(
                'user_agent'=>'firefox',
    
            );
            
            // delete test user
            $user = Application_user::model()->findByAttributes(array('device_token'=>$this->device_token));
            if($user)
                $user->delete();
        }

        public function testAddApplicationUser(){
            $api= new API();
            
            $add_user = $api->addApplicationUser($this->device_token, $this->uap_user_id, $this->type, $this->district_id, $this->optional);
            
            $this->assertTrue($add_user);
            
        }
        
        public function testUpdateApplicationUserMetaByDeviceToken(){
            $api= new API();
            
            $add_user = $api->addApplicationUser($this->device_token, $this->meta);
            
            $this->assertTrue($add_user);
            
        }

}

?>