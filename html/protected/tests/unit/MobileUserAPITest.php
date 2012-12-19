<?php

/**
 * Unitary tests for MobileUser
 *
 * @author Jonas
 */
class MobileUserAPITest extends CDbTestCase {

 //   private $mobileUserAPI1 = "http://www.voterguide.com/api/1/MobileUsers";
    
  private $mobileUserAPI1 = "http://23.24.252.203/api/1/MobileUsers";
    
    private $tenant1 = array(
        'username' => "52356", // api key
        'password' => "PqiW_IDKL3mFi_OirCqOe-u"// api secret
    );

    public function __construct() {
        
    }

    public function testCreateUser() {
        $response = "failure";

        $deviceIdentifier = md5(microtime());
        $userData = array(
            "name" => "jonas",
            "device_identifier" => $deviceIdentifier
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1);

        $response = $requestResult->results;

        $this->assertEquals("success", $response);
    }
    


    public function testUpdateUser() {

        $response = "failure";

        $deviceIdentifier = md5(microtime());
        $userData = array(
            "name" => "jonas",
            "device_identifier" => $deviceIdentifier
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1);

        $response = $requestResult->results;

        $this->assertEquals("success", $response);

        sleep(1);

        $response = null;

        $newUserData = array(
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $deviceIdentifier));


        $response = $requestResult->results;

        $this->assertEquals("success", $response);
    }


    public function testUpdateUserExistingFields() {

        $response = "failure";

        $deviceIdentifier = md5(microtime());
        $userData = array(
            "name" => "robert",
            "device_identifier" => $deviceIdentifier
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1);

        $response = $requestResult->results;

        $this->assertEquals("success", $response);

        $response = null;

        $newUserData = array(
            'set' => array(
                'name' => 'Robert Doisneau'
            )
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $deviceIdentifier));


        $response = $requestResult->results;

        $this->assertEquals("success", $response);
    }

    public function testUpdateUserAddCollections() {

        $response = "failure";

        $deviceIdentifier = md5(microtime());
        $userData = array(
            "name" => "robert",
            "device_identifier" => $deviceIdentifier,
            "tags" => array("default tag")
        );

        $jsonUserData = json_encode($userData);

        $data = array(
            "user" => $jsonUserData
        );

        $requestResult = $this->post($this->tenant1, $data, $this->mobileUserAPI1);

        $response = $requestResult->results;

        $this->assertEquals("success", $response);

        $response = null;

        $newUserData = array(
            'push' => array(
                'tags' => 'a new updated tag'
            )
        );

        $newJsonData = json_encode($newUserData);

        $newData = array(
            "user" => $newJsonData
        );

        $requestResult = json_decode($this->put($this->tenant1, $newData, $this->mobileUserAPI1 . '/' . $deviceIdentifier));


        $response = $requestResult->results;

        $this->assertEquals("success", $response);
    }

    private function post($credentials, $data, $endPoint) {

        $session = curl_init($endPoint);
        curl_setopt($session, CURLOPT_USERPWD, $credentials['username'] . ':' . $credentials['password']);
        curl_setopt($session, CURLOPT_POST, True);
        curl_setopt($session, CURLOPT_POSTFIELDS, $data);
        curl_setopt($session, CURLOPT_HEADER, False);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, True);
        $content = curl_exec($session);
        $response = curl_getinfo($session);

        $content = json_decode($content);

        curl_close($session);

        return $content;
    }

    private function put($credentials, $data, $endPoint) {
        $t = http_build_query($data);

        $session = curl_init($endPoint);
        curl_setopt($session, CURLOPT_USERPWD, $credentials['username'] . ':' . $credentials['password']);
        curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($session, CURLOPT_POSTFIELDS, $t);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($session);
        $response = curl_getinfo($session);

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

?>
