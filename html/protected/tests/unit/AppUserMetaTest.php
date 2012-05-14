<?php

Yii::import('application.controllers.ApiController');

class AppUserMetaTest extends CDbTestCase {
    private $application_user;
    
    public function __construct(){
        $this->application_user = Application_user::model()->findByPk(86);
    }
    
    
    public function testGetMeta(){
        
        $user_metas = $this->application_user->meta;
        
        
        error_log(print_r($user_metas, true));
        
        
        
        
    }
    
    
    
}

?>
