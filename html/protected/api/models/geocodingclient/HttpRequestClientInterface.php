<?php


interface HttpRequestClientInterface {
    public function getRequest($destination);
    
    public function postRequest($destination, $body, $options);
    
}


