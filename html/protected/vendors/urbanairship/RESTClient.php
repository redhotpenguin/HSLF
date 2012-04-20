<?php

/*
  Thanks George A. Papayiannis, most codes of this file are from
  http://www.sematopia.com/2006/10/how-to-making-a-php-rest-client-to-call-rest-resources/
 */
require_once "HTTP/Request2.php";

class RESTClient {

    private $curr_url = "";
    private $user_name = "";
    private $password = "";
    private $content_type = "";
    private $response = "";
    private $responseBody = "";
    private $responseCode = "";
    private $req = null;

    public function __construct($user_name = "", $password = "", $content_type = "") {
        $this->user_name = $user_name;
        $this->password = $password;
        $this->content_type = $content_type;
        return true;
    }

    public function createRequest($url, $method, $arr = null) {
        $this->curr_url = $url;
        $this->req = new HTTP_Request2($url);
        $this->req->setConfig('ssl_verify_peer', false);

        if ($this->user_name != "" && $this->password != "") {
            $this->req->setAuth($this->user_name, $this->password);
        }
        if ($this->content_type != "") {
            $this->req->setHeader("Content-Type", $this->content_type);
        }

        switch ($method) {
            case "GET":
                $this->req->setMethod('GET');
                break;
            case "POST":
                $this->req->setMethod('POST');

                $this->addPostData($arr);
                break;
            case "PUT":
                $this->req->setMethod('PUT');
                $this->addPostData($arr);
                break;
            case "DELETE":
                $this->req->setMethod('DELETE');
                break;
        }
    }

    private function addPostData($arr) {
        if ($arr != null) {
            if (gettype($arr) == 'string') {
                $this->req->setBody($arr);
            } else {
                foreach ($arr as $key => $value) {
                    $this->req->addPostData($key, $value);
                }
            }
        }
    }

    public function sendRequest() {
        try {
            $this->response = $this->req->send();
        } catch (Exception $e) {
            echo $e->getMessage();
            error_log("RestClient, sendRequest error:" . $e->getMessage());
        }


        $this->responseCode = $this->response->getStatus();
        $this->responseBody = $this->response->getBody();
    }

    public function getResponse() {
        return array($this->responseCode, $this->responseBody);
    }

}

?>
