<?php

/**
 * Description of ClientInfo
 *
 * @author jonas
 */

class ClientInfo {

    /**
     * Client Name
     * @var string 
     */
    protected $name;

    /**
     * Client Email
     * @var string 
     */
    protected $email;

    /**
     * Urban Airship API Key
     * @var string 
     */
    protected $apiKey;

    /**
     * Urban Airship API Secret
     * @var string 
     */
    protected $apiSecret;

    /**
     * ClientInfo
     * @param string $name client name
     * @param string $email client email
     * @param string $apiKey api key
     * @param string $apiSecret api secret
     */
    public function __construct($name, $email, $apiKey, $apiSecret) {

        if (empty($name))
            throw new \InvalidArgumentException("Client name is required");

        if (empty($email))
            throw new \InvalidArgumentException("Client email is required");

        if (empty($apiKey))
            throw new \InvalidArgumentException("Client api key is required");

        if (empty($apiSecret))
            throw new \InvalidArgumentException("Client api secret is required");


        $this->name = $name;
        $this->email = $email;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * getClientName
     * @return string client name
     */
    public function getName() {
        return $this->name;
    }

    /**
     * getClientEmail
     * @return string client email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * getClientApiKey
     * @return string client api key
     */
    public function getApiKey() {
        return $this->apiKey;
    }

    /**
     * getClientApiSecret
     * @return string client api secret
     */
    public function getApiSecret() {
        return $this->apiSecret;
    }

}

