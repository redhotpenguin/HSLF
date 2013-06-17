<?php

namespace UrbanAirship\lib;

abstract class UrbanAirshipClient {

    const UA_API = 'https://go.urbanairship.com/api';

    /**
     * API Key
     * @var string 
     */
    private $apiKey;

    /**
     * API Master Secret
     * @var string 
     */
    private $apiSecret;

    /**
     * Constructor
     * @param string $apiKey api key
     * @param string $apiSecret api secret
     */
    public final function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * GET JSON data from UA API
     * @param string $endPoint
     * @param boolean $rawEndPoint indicate whether or not the endpoint is a full link or not
     * @return result or throw exception
     */
    protected final function getJsonData($endPoint, $rawEndPoint = false) {


        if ($rawEndPoint == false) {
            $query = self::UA_API . $endPoint;
        }


        $ch = curl_init($query);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($status === 200) {
            return $result;
        }

        throw new Exception($result);
    }

    /**
     * Post JSON data to UA API
     * @param strign $endPoint
     * @param string $data (json format)
     * @return result or throw exception
     */
    protected final function postJsonData($endPoint, $data) {

        error_log("Sending following payload to Urban Airship: " . $data);

        $ch = curl_init(self::UA_API . $endPoint);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->apiSecret);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($status === 200) {
            return $result;
        }

        throw new Exception($result);
    }

    /**
     * Validate a Urban Airship ID (push id, segment_id)
     * @param string $id to be validated
     * @return boolean true if id is valid
     */
    protected final function validateId($id) {
        $pattern = "/^[a-zA-Z-0-9]{8}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{12}$/";

        return ( preg_match($pattern, $id) === 1 );
    }

}