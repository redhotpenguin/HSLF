<?php

/**
 * Unitary tests for MobileUser
 *
 * @author Jonas
 */
class MobileUserAPITest extends CDbTestCase {

    private $mobileUserAPI1 = "http://www.voterguide.com/api/1/MobileUsers";
  //  private $mobileUserAPI1 = "http://23.24.252.203/api/1/MobileUsers";
    private $tenant1 = array(
        'username' => "52356", // api key
        'password' => "PqiW_IDKL3mFi_OirCqOe-u"// api secret
    );

    public function testCreateUser() {
        $response = "failure";

        $userData = array(
            "name" => "jonas",
            "device_type" => "android",
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1, $httpCode);


        $response = $requestResult->results;

        $this->log($response);

        $this->assertEquals($httpCode, 200);


        $this->assertNotEmpty($response);
    }

    public function testCreateUserMissingRequiredParameter() {
        
        // device_type is missing
        $userData = array(
            "name" => "jonas invalid",
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1, $code);

        $response = $requestResult->results;
        
        $this->assertNotEquals("success", $response);

        $this->assertEquals(409, $code);
    }

    public function testUpdateUser() {

        $response = "failure";

        $userData = array(
            "name" => "jonas",
            "device_type" => "ios",
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1, $code);

        $newUserId = $requestResult->results->id;

        $this->assertEquals(200, $code);

        $this->assertNotEmpty($newUserId);

        sleep(1);


        $newUserData = array(
            'push' => array(
                'tags' => 'a new updated tag',
                'interest' => 'ping pong'
            )
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );


        $code = null;

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $newUserId, $code));

 //       $this->log($newData);

        $response = $requestResult->results;

        $this->assertEquals(200, $code);
    }

    public function testUpdateNotExistingUser() {

        $userData = array(
            "name" => "troll",
            "device_type" => "android",
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = json_decode($this->put($this->tenant1, $data, $this->mobileUserAPI1 . '/youwontfindme', $code));
  //      $this->log($requestResult);



        $response = $requestResult->results;

        $this->assertEquals(404, $code);

        $this->assertEquals("user not found", $response->error);
    }

    public function testUpdateUserExistingFields() {

        $response = "failure";

        $userData = array(
            "name" => "robert",
            "device_type" => "android",
            "age" => 41
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $result = $this->post($this->tenant1, $data, $this->mobileUserAPI1, $code);
        $userId = $result->results->id;



        $this->assertEquals(200, $code);

        $code = null;

        $newUserData = array(
            'set' => array(
                'name' => 'Robert Doisneau'
            )
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $userId, $code));



        $this->assertEquals(200, $code);
    }

    public function testUpdateUserAddCollections() {


        $userData = array(
            "name" => "Thomas",
            "device_type" => 'ios',
            "tags" => array("default tag")
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1, $code);

        $userId = $requestResult->results->id;


        $this->assertEquals(200, $code);

        $code = null;

        $newUserData = array(
            'push' => array(
                'tags' => 'a new updated tag'
            )
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $userId, $code));


        $response = $requestResult->results;

        $this->assertEquals(200, $code);
    }

    private function post($credentials, $data, $endPoint, &$httpResponseCode) {

        $session = curl_init($endPoint);
        curl_setopt($session, CURLOPT_USERPWD, $credentials['username'] . ':' . $credentials['password']);
        curl_setopt($session, CURLOPT_POST, True);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        curl_setopt($session, CURLOPT_HEADER, False);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
        $content = curl_exec($session);
        $response = curl_getinfo($session);


        $httpResponseCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        $content = json_decode($content);

        curl_close($session);

        return $content;
    }

    private function put($credentials, $data, $endPoint, &$httpResponseCode) {
        $t = http_build_query($data);

        $session = curl_init($endPoint);
        curl_setopt($session, CURLOPT_USERPWD, $credentials['username'] . ':' . $credentials['password']);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($session, CURLOPT_POSTFIELDS, $t);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($session);
        $response = curl_getinfo($session);
        $httpResponseCode = curl_getinfo($session, CURLINFO_HTTP_CODE);

        curl_close($session);

        return $content;
    }

    private function log($a) {
        if (is_object($a) || is_array($a)) {
            $a = print_r($a, true);
        }
        error_log($a);
    }

    private function dump($x) {
        ob_start();
        var_dump($x);
        $contents = ob_get_contents();
        ob_end_clean();
        error_log($contents);
    }

}

