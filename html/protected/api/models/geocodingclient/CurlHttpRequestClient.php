<?php

class CurlHttpRequestClient implements HttpRequestClientInterface {

    private $cURL;

    public function __construct() {
        $this->cURL = curl_init();
        // tell curl to be silent
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
    }

    public function getRequest($destination) {
        $this->setHttpMethod('GET');
        $this->setOption(CURLOPT_URL, $destination);
        $result = $this->execute();
        //@todo: error handling
        return $result;
    }

    public function postRequest($destination, $body, $options ) {
        $this->setHttpMethod('POST');
        $this->setOption(CURLOPT_URL, $destination);
        $this->setOption(CURLOPT_POSTFIELDS, $body);
        $result = $this->execute();
        //@todo: error handling
        return $result;
    }

    private function setHttpMethod($method = 'GET') {
        if ($method == 'GET')
            curl_setopt($this->cURL, CURLOPT_HTTPGET, true);
        elseif ($method == 'POST')
            curl_setopt($this->cURL, CURLOPT_POST, true);
        else
            curl_setopt($this->cURL, CURLOPT_PUT, true);
    }

    private function execute() {
        return curl_exec($this->cURL);
    }

    protected function closeCurl() {
        return curl_close($this->cURL);
    }

    public function __destruct() {
        $this->closeCurl();
    }

    public function setOption($option, $value) {
        curl_setopt($this->cURL, $option, $value);
    }

}

