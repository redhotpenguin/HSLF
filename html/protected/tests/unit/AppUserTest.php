<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AppUserTest extends CDbTestCase {
    private $app_user_id = 70;


    
    public function testGetInvalidTag() {
        $result = Application_user::model()->findByPk(70)->getMeta('ta41g', true);
        $this->assertEquals(false, $result);
    }
    
    
    public function testAddMeta(){
        $meta_key = 'FOO';
        $meta_value = 'BAR';
          
        $add_result = Application_user::model()->findByPk($this->app_user_id)->addMeta($meta_key, $meta_value);
        $this->assertEquals(true, $add_result);
    }
    
 
    public function testUpdateSingleMeta(){
        $update_result = Application_user::model()->findByPk($this->app_user_id)->updateMeta('FOO','ok', 'BAR');
        $this->assertEquals(true, $update_result);
    }
    
   public function testGetSingleUserMeta() {
        $result = Application_user::model()->findByPk(70)->getMeta('FOO',  true);
        $this->assertEquals('ok', $result['meta_value']);
    }
    
   public function testGetAllUserMeta() {
        $result = Application_user::model()->findByPk(70)->getMeta('FOO', false);
        $this->assertEquals('ok', $result[0]['meta_value']);
    }
    
    public function testDeletMeta(){
        $delete_result = Application_user::model()->findByPk($this->app_user_id)->deleteMeta('FOO', 'ok');
        $this->assertEquals(true, $delete_result);
    }
    
    
    public function testUpdateNewMeta(){
        $update_result = Application_user::model()->findByPk(70)->updateMeta('name', 'jonas palmeeero');
        $this->assertEquals(true, $update_result);
    }
    
}