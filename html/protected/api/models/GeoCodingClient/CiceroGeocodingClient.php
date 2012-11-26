<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CiceroGeocodingClient
 * Implementation of the IGeocoding interface
 *
 * @author jonas
 */
class CiceroGeoCodingClient implements GeoCodingClientInterface {

    private $username;
    private $password;
    private $userId;
    private $token;
    private $httpRequestClient;
    private $apiBase = "http://cicero.azavea.com/v3.1";

    public function __construct(HttpRequestClientInterface $httpRequestClient) {
        $this->httpRequestClient = $httpRequestClient;


        $this->username = CICERO_USERNAME;
        $this->password = CICERO_PASSWORD;

        //@todo: check if any of those two values are empty and call updateToken

        if (( $row = Option::model()->findByAttributes(array('name' => 'cicero_user')) ) != null) {
            $this->userId = $row->value;
        }

        if (( $row = Option::model()->findByAttributes(array('name' => 'cicero_token')) ) != null) {
            $this->token = $row->value;
        }

        if (empty($this->userId) || empty($this->token)) {
            echo 'updating DB';
            $this->updateCredentials();
        }
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
                // upsert_option is a custom postesgsql function
                $update_cicero_token_query = "SELECT upsert_option('cicero_token', '$this->token')";
                $update_cicero_user_query = "SELECT upsert_option('cicero_user', '$this->userId')";

                $connection->createCommand($update_cicero_token_query)->execute();
                $connection->createCommand($update_cicero_user_query)->execute();

                // commit the transaction
                $transaction->commit();
                return true;
            } catch (Exception $e) {
                $result = $e->getMessage();
                return false;
            }
        }
        else
            throw new Exception("Wrong Credential");
    }

    // return district IDs
    public function getDistrictsByAddress($address, $options = array()) {

        static $failureNumber = 0;

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
        if (empty($response) && $failureNumber == 0) {
            $this->updateCredentials();
            $response = $this->getDistrictsByAddress($address, $options);
        }

        $jsonResponse = json_decode($response);

        if (!isset($jsonResponse->response->results->candidates[0])) {
            return false;
        }

        $ciceroDistricts = $jsonResponse->response->results->candidates[0]->districts;


        return $this->ciceroDistrictsToDistricts($ciceroDistricts);
    }

    private function ciceroDistrictsToDistricts(array $ciceroDistricts) {

        $state = "";
        $districtTypes = array("STATEWIDE");
        $districtNumbers = array("");
        $localities = array();

        $i = 0;

        foreach ($ciceroDistricts as $ciceroDistrict) {
            $state = $ciceroDistrict->state;
            array_push($districtTypes, $ciceroDistrict->subtype);
            array_push($districtNumbers, $ciceroDistrict->district_id);
        }

        $districtIds = DistrictManager::getIdsByDistricts($state, $districtTypes, $districtNumbers, $localities);

        return $districtIds;
    }

    public function getDistrictsByLatLong($lat, $long, $options = array()) {
        static $failureNumber = 0;


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
        if (empty($response) && $failureNumber == 0) {
            $this->updateCredentials();
            $response = $this->getDistrictsByAddress($address, $options);
        }

        $jsonResponse = json_decode($response);


        if (!isset($jsonResponse->response->results->districts)) {
            return false;
        }

        $ciceroDistricts = $jsonResponse->response->results->districts;


        return $this->ciceroDistrictsToDistricts($ciceroDistricts);
    }

}
