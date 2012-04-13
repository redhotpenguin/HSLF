<?php

// Php module for using the Urban Airship API

require_once(dirname(__FILE__) . '/RESTClient.php');

define('SERVER', 'go.urbanairship.com');
define('BASE_URL', 'https://go.urbanairship.com/api');
define('DEVICE_TOKEN_URL', BASE_URL . '/device_tokens/');
define('APID_URL', BASE_URL . '/apids/');
define('PUSH_URL', BASE_URL . '/push/');
define('BROADCAST_URL', BASE_URL . '/push/broadcast/');
define('FEEDBACK_URL', BASE_URL . '/device_tokens/feedback/');
define('TAGS', BASE_URL . '/tags/');

// Raise when we get a 401 from the server.
class Unauthorized extends Exception {
    
}

// Raise when we get an error response from the server.
// args are (status code, message).
class AirshipFailure extends Exception {
    
}

class AirshipDeviceList implements Iterator, Countable {

    private $_airship = null;
    private $_page = null;
    private $_position = 0;

    public function __construct($airship, $url) {
        $this->_airship = $airship;
        $this->_load_page($url);
        $this->_position = 0;
    }

    private function _load_page($url) {
        $response = $this->_airship->_request($url, 'GET', null, null);
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        $result = json_decode($response[1]);
        if ($this->_page == null) {
            $this->_page = $result;
        } else {
            $this->_page->device_tokens = array_merge($this->_page->device_tokens, $result->device_tokens);
            $this->_page->next_page = $result->next_page;
        }
    }

    // Countable interface
    public function count() {
        return $this->_page->device_tokens_count;
    }

    // Iterator interface
    function rewind() {
        $this->_position = 0;
    }

    function current() {
        return $this->_page->device_tokens[$this->_position];
    }

    function key() {
        return $this->_position;
    }

    function next() {
        ++$this->_position;
    }

    function valid() {
        if (!isset($this->_page->device_tokens[$this->_position])) {
            $next_page = isset($this->_page->next_page) ? $this->_page->next_page : null;
            if ($next_page == null) {
                return false;
            } else {
                $this->_load_page($next_page);
                return $this->valid();
            }
        }
        return true;
    }

}

class Airship {

    private $key = '';
    private $secret = '';

    public function __construct($key, $secret) {
        $this->key = $key;
        $this->secret = $secret;
        return true;
    }

    public function _request($url, $method, $body = null, $content_type = null) {

        //   echo $url;
        //   echo '<br>';

        $rest = new RESTClient($this->key, $this->secret, $content_type);
        $rest->createRequest($url, $method, $body);
        $rest->sendRequest();
        $response = $rest->getResponse();
        if ($response[0] == 401) {
            throw new Unauthorized();
        }
        return $response;
    }

    // Register the device token with UA.
    public function register_ios($device_token, $alias = null, $tags = null, $badge = null) {
        $url = DEVICE_TOKEN_URL . $device_token;
        $payload = array();
        if ($alias != null) {
            $payload['alias'] = $alias;
        }
        if ($tags != null) {
            $payload['tags'] = $tags;
        }
        if ($badge != null) {
            $payload['badge'] = $badge;
        }
        if (count($payload) != 0) {
            $body = json_encode($payload);
            $content_type = 'application/json';
        } else {
            $body = '';
            $content_type = null;
        }
        $response = $this->_request($url, 'PUT', $body, $content_type);
        $response_code = $response[0];
        if ($response_code != 201 && $response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        return ($response_code == 201);
    }

    // Register the device token with UA.
    public function register_android($device_token, $alias = null, $tags = null) {
        $url = APID_URL . $device_token;
        $payload = array();
        if ($alias != null) {
            $payload['alias'] = $alias;
        }
        if ($tags != null) {
            $payload['tags'] = $tags;
        }

        if (count($payload) != 0) {
            $body = json_encode($payload);
            $content_type = 'application/json';
        } else {
            $body = '';
            $content_type = null;
        }
        $response = $this->_request($url, 'PUT', $body, $content_type);
        $response_code = $response[0];
        if ($response_code != 201 && $response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        return ($response_code == 201);
    }

    // Mark the device token as inactive.
    public function deregister($device_token) {
        $url = DEVICE_TOKEN_URL . $device_token;
        $response = $this->_request($url, 'DELETE', null, null);
        $response_code = $response[0];
        if ($response_code != 204) {
            throw new AirshipFailure($response[1], $response_code);
        }
    }

    // Retrieve information about this device token.
    public function get_device_token_info($device_token) {
        $url = DEVICE_TOKEN_URL . $device_token;
        $response = $this->_request($url, 'GET', null, null);
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        return json_decode($response[1]);
    }

    public function get_device_tokens() {
        return new AirshipDeviceList($this, DEVICE_TOKEN_URL);
    }

    public function get_apids() {
        return new AirshipDeviceList($this, APID_URL);
    }

    // Push this payload to the specified device tokens and tags.
    public function push_ios($payload, array $device_tokens = null, array $aliases = null, array $tags = null) {

        if ($device_tokens != null) {
            $payload['device_tokens'] = $device_tokens;
        }
        if ($aliases != null) {
            $payload['aliases'] = $aliases;
        }
        if ($tags != null) {
            $payload['tags'] = $tags;
        }
        $body = json_encode($payload);
        $response = $this->_request(PUSH_URL, 'POST', $body, 'application/json');
        $response_code = $response[0];

        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    // Push this payload to the specified device tokens and tags.
    public function push_android($alert, array $apids, array $tags = NULL, array $aliases = NULL, $extra = NULL) {
        $payload = array();
        $payload['apids'] = $apids;


        if ($aliases) {
            $payload['aliases'] = $aliases;
        }

        if ($tags) {
            $payload['tags'] = $tags;
        }

        if ($extra) {
            $payload['android']['extra'] = $extra;
        }

        $payload['android']['alert'] = $alert;

        $body = json_encode($payload);

        $response = $this->_request(PUSH_URL, 'POST', $body, 'application/json');
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    // Broadcast this payload to all users.
    public function broadcast_ios($payload, array $exclude_tokens = null) {
        if ($exclude_tokens != null) {
            $payload['exclude_tokens'] = $exclude_tokens;
        }
        $body = json_encode($payload);
        $response = $this->_request(BROADCAST_URL, 'POST', $body, 'application/json');
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    public function broadcast_android($alert, $extra = null) {
        $payload['android']['alert'] = $alert;
        if ($extra) {
            $payload['android']['extra'] = $extra;
        }

        $body = json_encode($payload);

        $response = $this->_request(BROADCAST_URL, 'POST', $body, 'application/json');
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    /*
      Return device tokens marked as inactive since this timestamp
      Return a list of (device token, timestamp, alias) functions.
     */

    public function feedback($since) {
        $url = FEEDBACK_URL . '?' . 'since=' . rawurlencode($since->format('c'));
        $response = $this->_request($url, 'GET', null, null);
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        $results = json_decode($response[1]);
        foreach ($results as $item) {
            $item->marked_inactive_on = new DateTime($item->marked_inactive_on,
                            new DateTimeZone('UTC'));
        }
        return $results;
    }

    /* TAGS */

    public function get_json_tags() {
        $response = $this->_request(TAGS, 'GET');

        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        else
            return $response[1];
    }

    public function create_tag($tag_name) {

        $response = $this->_request(TAGS . $tag_name, 'PUT');

        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }
        return $response_code;
    }

    public function add_device_tag($tag, $device, $type = 'ios') {
        if ($type == 'ios') {
            $request_url = DEVICE_TOKEN_URL . $device . '/tags/' . $tag;
            $response = $this->_request($request_url, 'PUT');
            $response_code = $response[0];
            error_log('add device');
            error_log(  print_r($response, true) );
            if ($response_code == 'Not Found') {
                throw new AirshipFailure($response[1], $response_code);
            }
            return $this->_validate_http_code($response_code);
        } elseif ($type == 'android') {
            $payload = array(
                'apids' => array(
                    'add' => array($device),
                ),
            );
            return $r = $this->update_devices_tag($tag, $payload);
        } else {
            return false;
        }
    }

    public function delete_device_tag($tag, $device, $type = 'ios') {
        if ($type == 'ios') {
            $request_url = DEVICE_TOKEN_URL . $device . '/tags/' . $tag;
            $response = $this->_request($request_url, 'DELETE');
   
        
            $response_code = $response[0];
      

            if ($response_code != true) {
                throw new AirshipFailure($response[1], $response_code);
            }

            return $this->_validate_http_code($response_code);
        } elseif ($type == 'android') {
            $payload = array(
                'apids' => array(
                    'remove' => array($device),
                ),
            );
            return $this->update_devices_tag($tag, $payload);
        }
    }

    public function update_devices_tag($tag, $payload) {
        /*
          $payload = array(
          'device_tokens' => array(
          'add' => array(),
          'remove' => array(),
          ),
          'apids' => array(
          'add' => array('b13de54c-7478-49cc-8db3-3f284209ed4f'),
          'remove' => array(),
          ),
          );
         */
        $json_payload = json_encode($payload);

        $request_url = TAGS . $tag;

        $response = $this->_request($request_url, 'POST', $json_payload, 'application/json');
        $response_code = $response[0];
        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    public function push_to_tags($alert, array $tags) {
        $payload = array();

        $payload['aps'] = array(
            'alert' => $alert
        );

        $payload['android'] = array(
            'alert' => $alert
        );

        $payload['tags'] = $tags;

        $json_payload = json_encode($payload);

        $response = $this->_request(PUSH_URL, 'POST', $json_payload, 'application/json');
        $response_code = $response[0];

        if ($response_code != 200) {
            throw new AirshipFailure($response[1], $response_code);
        }

        return $response_code;
    }

    /*
      return true when code = 200, 201, or 204
      else return false
     */

    private function _validate_http_code($code) {
        return ($code == 200 || $code == 201 || $code == 204);
    }

}

?>