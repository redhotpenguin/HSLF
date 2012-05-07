<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.controllers.ApiController');

class AppUserTagTest extends CDbTestCase {

    private $app_user;
    public $tag1_id;
    public $tag2_id;
    public $tag_name = 'TheDude';
    public $tag2_name = 'yay';

    public function __construct() {




        $this->app_user = Application_user::model()->findByPk(76);
    }

    public function _testAddAppUserTagUsingTagID() {
        $result = false;

        $tag_id = Tag::model()->findByAttributes(array('name' => $this->tag2_name))->id;

        $result = $this->app_user->addTag($tag_id);

        $this->assertEquals(true, $result);
    }

    public function _testAddAppUserTagUsingTagName() {
        $result = false;
        $tag_name = 'french';
        $result = $this->app_user->addTag($this->tag_name);
        $this->assertEquals(true, $result);
    }

    public function _testGetUserTags() {
        error_log('Test Get User Tags');
        $app_user = Application_user::model()->with('tags')->findByPk(76);

        foreach ($app_user->tags as $tag) {
            error_log($tag->name);
        }

        $this->assertEquals($this->tag_name, $app_user->tags[0]->name);
    }

    public function _testDeleteUserTag() {
        error_log('Test Delete User Tag');
        $app_user = Application_user::model()->with('tags')->findByPk(76);

        $result = $app_user->deleteTag(6);


        $this->assertEquals(true, $result);
    }
    
    public function _testFindTag(){
        $result = $this->app_user->findTag('TheDude');
             
        $this->assertEquals(5, $result); 
    }
    
        
    public function testFindNonExistingTag(){
        $result = $this->app_user->findTag('TheDqqude');
             
        $this->assertEquals(false, $result); 
    }
    
    
    public function testUpdateLocation(){
        $result = $this->app_user->updateLocation('al', 4);
             
        $this->assertEquals(true, $result); 
        
    }
}

?>