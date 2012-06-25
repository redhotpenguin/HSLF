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

    public function testUpdateApplicationUserMeta() {

        //$add_meta = $this->app_user->addMeta('foo','bar');
       // $this->assertTrue($add_meta);

        $update_meta = $this->app_user->updateMeta('email', 'toto.com');
        $this->assertTrue($update_meta);
        
      //  error_log( print_r( $this->app_user->getMeta('foo', true ), true ) ) ;
    }

    public function testUpdateMultipleMeta(){
        $metas = array(
            '1' => 'un',
            '2' => 'deux',
        );
        
        $this->app_user->updateMassMeta($metas);
    }
    
    public function testApplicationUserTag(){
        $add_tag = $this->app_user->addTag('tag2');
        $this->assertTrue($add_tag);
    }
    
    
    public function _testGetSingleTag(){
        error_log($this->app_user->findTag('tag1'));
    }
    
    public function _testDeleteTag(){
        $delete_tag = $this->app_user->deleteTag('tag1');
        $this->assertTrue($delete_tag);
    }
    
    public function testSynchTag(){
         $t = $this->app_user->synchronizeUAPTags();
         $this->assertTrue($t);
    }
}

?>