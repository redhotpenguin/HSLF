<?php

class RestException extends Exception {

    public function __construct($httpCode = 0, $message = null, Exception $previous = null) {

        if (!$message) {
            $message = $this->getMessageFromHttpCode($httpCode);
        }

        parent::__construct($message, $httpCode, $previous);
    }

    private function getMessageFromHttpCode($httpCode) {
        switch ($httpCode) {
            case 400 : $message = "Bad Request";
                break;
            case 404 : $message = "Not Found";
                break;
            case 409 : $message = "Conflict";
                break;
            case 500 : $message = "Internal Server Error";
                break;
            case 501 : $message = "Not Implemented";
                break;

            default : $message = "Internal Server Error";
                break;
        }

        return $message;
    }

}