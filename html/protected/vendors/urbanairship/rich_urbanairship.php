<?php

require_once(dirname(__FILE__) . '/RESTClient.php');

define('SERVER', 'go.urbanairship.com');
define('BASE_URL', 'https://go.urbanairship.com/api');
define('USER_URL', BASE_URL . '/user');

define('ALIAS_URL', USER_URL . '/alias');

define('AIRMAIL_URL', BASE_URL . '/airmail');
define('AIRMAIL_SEND_URL', AIRMAIL_URL . '/send/');

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


    public function sendRichNotification(array $audience, array $airmail_payload, $alert = null, array $extra = null ) {
        // at least one of tags, users or aliases must be specified.
        if( !isset($audience['users']) && !isset($audience['tags']) && !isset($audience['users']) )
            throw new Exception('An audience is required');
        
   
        if(!isset($airmail_payload['message']))
            throw new Exception('A message must be specified in the payload');

        $payload = array();

        if (isset($audience['users']))
            $payload['users'] = $audience['users'];

        if (isset($audience['tags']))
            $payload['tags'] = $audience['tags'];

        if (isset($audience['aliases']))
            $payload['aliases'] = $audience['aliases'];

        
        if (!empty($alert)) {
            $payload['push'] = array(
                'aps' => array(
                    'alert' => $alert
                )
            );
        }
        if (!empty($airmail_payload)) {
            $payload['title'] = $airmail_payload['title'];
            $payload['message'] = $airmail_payload['message'];
            $payload['content_type'] = 'text/html';
        }
        
        if( !empty($extra)) {
            $payload['extra'] = $extra;
        }


        $json_payload = json_encode($payload);

        //error_log($json_payload);

       $response = $this->_request(AIRMAIL_SEND_URL, 'POST', $json_payload, 'application/json');
       $response_code = $response[0];
       
       if($response_code != 200)
        throw new RichAirshipFailure($response[1], $response_code);
       

        return $this->_validate_http_code($response_code);
    }

    private function _validate_http_code($code) {
        return ($code == 200 || $code == 201 || $code == 204);
    }

}

?>