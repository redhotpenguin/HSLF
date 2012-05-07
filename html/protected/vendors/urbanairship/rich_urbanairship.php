<?php

// Php module for using the Urban Airship API

require_once(dirname(__FILE__) . '/RESTClient.php');

define('SERVER', 'go.urbanairship.com');
define('BASE_URL', 'https://go.urbanairship.com/api');
define('USER_URL', BASE_URL . '/user');

define('ALIAS_URL', USER_URL . '/alias');

// Raise when we get a 401 from the server.
class RichUnauthorized extends Exception {
    
}

class RichAirshipFailure extends Exception {
    
}

class Rich_Airship {

    private $key = '';
    private $secret = '';

    public function __construct($key, $secret) {
        $this->key = $key;
        $this->secret = $secret;
        return true;
    }

    public function _request($url, $method, $body = null, $content_type = null) {

        $rest = new RESTClient($this->key, $this->secret, $content_type);
        $rest->createRequest($url, $method, $body);
        $rest->sendRequest();
        $response = $rest->getResponse();
        if ($response[0] == 401) {
            throw new Unauthorized();
        }
        return $response;
    }

    public function update_device_tags(array $tags, $device_token, $user_id, $device_type = 'ios') {   
        if ($device_type == 'ios') {
            $url = USER_URL . '/' . $user_id . '/';

            $body = array(
                'tags' => $tags,
                'device_tokens' => array($device_token),
            );

            $body = json_encode($body);
            $response = $this->_request($url, 'POST', $body, 'application/json');

            return $this->_validate_http_code($response[0]);
        } else {
            error_log('Feature not supported');
            return false;
        }
    }

    public function getUsers() {


        $url = BASE_URL;
        $response = $this->_request($url, 'GET', null, null);
        // error_log($url);
        //  error_log(print_r($response, true));

        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return json_decode($response[1]);
    }

  

    private function _validate_http_code($code) {
        return ($code == 200 || $code == 201 || $code == 204);
    }

}

?>