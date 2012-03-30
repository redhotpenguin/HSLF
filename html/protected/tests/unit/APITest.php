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

    public function testGetCandidates() {

        // we use the reflection api to test private methods
        $method = $this->api_reflection->getMethod('_getCandidates');
        $method->setAccessible(true);

        $method_params = array(
            'state_abbr' => 'AL',
            'district_number' => 4
        );


        $method_result = $method->invokeArgs($this->api_controller, array($method_params, 'test'));

        $this->assertEquals('Robert Aderholt', $method_result[0]->full_name);
    }

    public function testRegisterAppUser() {
        $test_token = '666';

        $method = $this->api_reflection->getMethod('actionCreate');


        $_POST = array(
            'state_abbr' => 'AL',
            'district_number' => 4
        );

        $_GET['model'] = 'app_users';
        $_POST['device_token'] = $test_token;
        $_POST['state_abbr'] = 'AL';
        $_POST['district_number'] = 4;
        $_POST['type'] = 'android';


       // $method->invokeArgs($this->api_controller, array());

        //todo: test that app_user can get updated

        $application_user = Application_users::model()->findByAttributes(array('device_token' => $test_token));

        $this->assertEquals('android', $application_user->type);
    }

}
?>