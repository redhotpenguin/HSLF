<?php

/**
 * RestFailure
 * Represent an invalid rest response
 */
class RestFailure {

    private $reason;
    private $httpCode;

    public function __construct($httpCode, $reason = null) {
        $this->httpCode = $httpCode;

        $this->reason = $reason;
    }

    public function getReason() {
        return $this->reason;
    }

    public function getHttpCode() {
        return $this->httpCode;
    }

    public function setReason($reason) {
        $this->reason = $reason;
    }

    public function setHttpCode($httpCode) {
        $this->httpCode = $httpCode;
    }

}