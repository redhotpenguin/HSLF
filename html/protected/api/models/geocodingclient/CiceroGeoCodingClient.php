<?php

class CiceroGeoCodingClient implements GeoCodingClientInterface {

    private $username;
    private $password;
    private $userId;
    private $token;
    private $httpRequestClient;
    private $apiBase = "http://cicero.azavea.com/v3.1";
    private $tenantId;

    public function __construct(HttpRequestClientInterface $httpRequestClient, $options = array()) {
        $this->httpRequestClient = $httpRequestClient;


        $this->username = $options['username'];
        $this->password = $options['password'];
        $this->tenantId = $options['tenantId'];

        //@todo: check if any of those two values are empty and call updateToken

        if (( $row = Option::model()->findByAttributes(array('name' => 'cicero_user')) ) != null) {
            $this->userId = $row->value;
        }

        if (( $row = Option::model()->findByAttributes(array('name' => 'cicero_token')) ) != null) {
            $this->token = $row->value;
        }

        if (empty($this->userId) || empty($this->token)) {
            throw new CiceroGeoCodingClientException("Missing user id or token");
        }
    }

    // return district IDs
    public function getDistrictIdsByAddress($address, $options = array()) {
        // replace white spaces in the address
        $address = str_replace(' ', '%20', $address);

        if (isset($options['requesting']))
            $requesting = $options['requesting'];
        else
            $requesting = 'nonlegislative_district';

        if (isset($options['type']))
            $type = $options['type'];
        else
            $type = 'CENSUS';

        if (isset($options['format']))
            $format = $options['format'];
        else
            $format = 'json';


        $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&search_loc={$address}";

        $response = $this->httpRequestClient->getRequest($destination);

        if ($response == null || empty($response)) {
            return false;
        }

        $jsonResponse = json_decode($response);

        if (isset($jsonResponse->response->errors) && !empty($jsonResponse->response->errors)) {
            throw new CiceroGeoCodingClientException("Can't retrieve districts: " . $jsonResponse->response->errors[0]);
        }

        if (!isset($jsonResponse->response->results->candidates[0]->districts) || empty($jsonResponse->response->results->candidates[0]->districts)) {
            return false;
        }

        return $jsonResponse->response->results->candidates[0]->districts;
    }

    public function getDistrictIdsByLatLong($lat, $long, $options = array()) {
        //nonlegislative_district?f=<format>&user=<user>&token=<token>&type=<TYPE>&lat=<lat>&lon=<lon>
        if (empty($lat) || empty($long))
            throw new Exception("LAT_LONG_REQUIRED");

        if (isset($options['requesting']))
            $requesting = $options['requesting'];
        else
            $requesting = 'nonlegislative_district';

        if (isset($options['type']))
            $type = $options['type'];
        else
            $type = 'CENSUS';

        if (isset($options['format']))
            $format = $options['format'];
        else
            $format = 'json';

        $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&lat={$lat}&lon={$long}";
        $response = $this->httpRequestClient->getRequest($destination);

        if ($response == null || empty($response)) {
            return false;
        }

        $jsonResponse = json_decode($response);


        if (isset($jsonResponse->response->errors) && !empty($jsonResponse->response->errors)) {
            throw new CiceroGeoCodingClientException("Can't retrieve districts: " . $jsonResponse->response->errors[0]);
        }

        if (!isset($jsonResponse->response->results->districts)) {
            return false;
        }

        return  $jsonResponse->response->results->districts;

    }

}
