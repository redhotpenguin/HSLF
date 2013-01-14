<?php

/**
 * RestFailure
 * Represent an invalid rest response
 */
class RestFailure {

    private $reason;
    private $httpCode;

    /**
     * @const Bad Request
     */

    const HTTP_BAD_REQUEST_CODE = 400;

    /**
     * @const Conflict
     */
    const HTTP_CONFLICT_CODE = 409;

    /**
     * @const Not Found
     */
    const HTTP_NOT_FOUND_CODE = 404;
    
    
    /**
     * @const Internel Server error
     */
    const HTTP_INTERNAL_ERROR_CODE = 500;
    

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