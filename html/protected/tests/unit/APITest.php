<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('application.controllers.ApiController');

class APITest extends CDbTestCase {

    private $api_reflection;
    private $api_controller;

    public function __construct() {
        $this->api_reflection = new ReflectionClass('ApiController');
        $this->api_controller = new ApiController('test');
    }

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        PHPUnit_Framework_Error_Notice::$enabled = false;
    }

    public function testGetCandidate() {

        // we use the reflection api to test private methods
        $method = $this->api_reflection->getMethod('_getCandidates');
        $method->setAccessible(true);

        $method_params = array(
            'state_abbr' => 'az',
            'district_number' => 1
        );

        $method_result = $method->invokeArgs($this->api_controller, array($method_params, 'test'));
        $this->assertEquals('Ann Kirkpatrick', $method_result[0]->full_name);
    }

    public function testRegisterAppUser() {
        $test_token = '666';

        $method = $this->api_reflection->getMethod('actionCreate');

        $post_data  = array(
            'device_token' => $test_token,
            'state_abbr' => 'ca',
            'district_number' => '14',
            'type' => 'ios',
            'user' => 'secretuser',
            'password' => 'secretpassword',
        );
        
        $this->pushPostData('http://www.voterguide.com/api/app_users/', $post_data);

        $application_user = Application_users::model()->findByAttributes(array('device_token' => $test_token));

        $this->assertEquals('ios', $application_user->type);
    }

    public function testUpdateAppUser(){
        $test_token = '666';

        $method = $this->api_reflection->getMethod('actionCreate');

        $post_data  = array(
            'device_token' => $test_token,
            'state_abbr' => 'ca',
            'district_number' => '13',
            'type' => 'ios',
            'user' => 'secretuser',
            'password' => 'secretpassword',
        );
        
        $this->pushPostData('http://www.voterguide.com/api/app_users/', $post_data);

        $application_user = Application_users::model()->findByAttributes(array('device_token' => $test_token));
        $this->assertEquals('ios', $application_user->type);
        $this->assertEquals('13', $application_user->district->number);
        
    }
    
    
    public function pushPostData($url, array $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
    }
}

?>