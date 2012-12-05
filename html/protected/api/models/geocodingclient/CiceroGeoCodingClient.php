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
            $this->updateCredentials();
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

        // no response, probably because the token has expired
        if ($response == null || empty($response)) {
            $this->updateCredentials();
            // update query with new credentials
            $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&search_loc={$address}";
            $response = $this->httpRequestClient->getRequest($destination);
        }

        $jsonResponse = json_decode($response);

        if (isset($jsonResponse->response->errors) && !empty($jsonResponse->response->errors)) {
            // update query with new credentials
            $this->updateCredentials();
            $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&search_loc={$address}";
            $response = $this->httpRequestClient->getRequest($destination);
        }

        if (!isset($jsonResponse->response->results->candidates[0]->districts) || empty($jsonResponse->response->results->candidates[0]->districts)) {
            return false;
        }

        $ciceroDistricts = $jsonResponse->response->results->candidates[0]->districts;

        return $this->ciceroDistrictsToDistricts($ciceroDistricts);
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

        // no response, probably because the token has expired
        if ($response == null || empty($response)) {
            $this->updateCredentials();
            $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&lat={$lat}&lon={$long}";


            $response = $this->httpRequestClient->getRequest($destination);
        }

        $jsonResponse = json_decode($response);

        if (isset($jsonResponse->response->errors) && !empty($jsonResponse->response->errors)) {
            $this->updateCredentials();
            // update query with new credentials
            $destination = "{$this->apiBase}/{$requesting}?f={$format}&user={$this->userId}&token={$this->token}&type={$type}&lat={$lat}&lon={$long}";
            $response = $this->httpRequestClient->getRequest($destination);
        }


        if (!isset($jsonResponse->response->results->districts)) {
            return false;
        }

        $ciceroDistricts = $jsonResponse->response->results->districts;


        return $this->ciceroDistrictsToDistricts($ciceroDistricts);
    }

    private function updateCredentials() {

        if (empty($this->username) || empty($this->password))
            throw new Exception("Cicero Credential required (check config.php)");


        $ciceroJsonResult = $this->httpRequestClient->postRequest($this->apiBase . '/token/new.json', "username={$this->username}&password={$this->password}", array());

        $result = json_decode($ciceroJsonResult);

        // we have a new userId and token, save it in the DB
        if (isset($result->success) && $result->success == 1) {
            $this->token = $result->token;
            $this->userId = $result->user;

            $connection = Yii::app()->db;

            $transaction = $connection->beginTransaction();
            try {
                $opt = new Option();
                $opt->sessionTenantId = $this->tenantId;
                $opt->upsert('cicero_token', $this->token);
                $opt = new Option();
                $opt->sessionTenantId = $this->tenantId;
                $opt->upsert('cicero_user', $this->userId);
                $transaction->commit();

                return true;
            } catch (Exception $e) {
                $transaction->rollback();
                $result = $e->getMessage();
                error_log("failed saving cicero credentials: " . $result);
                return false;
            }
        }
        else
            throw new Exception("Wrong Credential");
    }

    private function ciceroDistrictsToDistricts(array $ciceroDistricts) {

        $state = "";
        $districtTypes = array("STATEWIDE");
        $districtNumbers = array("");
        $localities = array();

        foreach ($ciceroDistricts as $ciceroDistrict) {
            $state = $ciceroDistrict->state;
            array_push($districtTypes, $ciceroDistrict->subtype);
            array_push($districtNumbers, $ciceroDistrict->district_id);
        }

        $districtIds = District::model()->getIdsByDistricts($state, $districtTypes, $districtNumbers, $localities);

        return $districtIds;
    }

}
