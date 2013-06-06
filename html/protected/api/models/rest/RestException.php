<?php

class RestException extends Exception {
    
    private  $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            409 => 'Conflict',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
     );

    public function __construct($httpCode = 0, $message = null, Exception $previous = null) {

        if (!$message) {
            $message = $this->getMessageFromHttpCode($httpCode);
        }

        parent::__construct($message, $httpCode, $previous);
    }

    private function getMessageFromHttpCode($httpCode) {
       return isset($this->codes[$httpCode]) ? $this->codes[$httpCode] : $this->codes[500];
    }

}