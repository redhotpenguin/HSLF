<?php

class ApiController extends Controller {

    const APPLICATION_ID = 'MOBILE API';
    const API_VERSION = '2.0';

    public function actionIndex() {
        $this->sendResponse(404);
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array();
    }

    /**
     * List supported models
     */
    public function actionList($tenant_id) {

        $requested_model = $_GET['model'] . 'API';

        if (!class_exists($requested_model)) {
            $code = 404;
            $message = "Not supported";
        } else {
            $code = 200;
            $model = new $requested_model();
            unset($_GET['model']);

            if ($this->checkAuth())
                $model->setAuthenticated(true);
            else
                $model->setAuthenticated(false);

            $message = $model->getList($tenant_id, $_GET);
        }

        $this->sendResponse($code, $message);
    }

    /**
     * List models according to a specific request
     */
    public function actionView($tenant_id, $model, $id) {
        $requested_model = $model . 'API';

        if (!class_exists($requested_model)) {
            $code = 404;
            $message = "Not supported";
        } else {
            $code = 200;
            $model = new $requested_model();

            if ($this->checkAuth())
                $model->setAuthenticated(true);
            else
                $model->setAuthenticated(false);

            unset($_GET['model']);
            $message = $model->getSingle($id, $_GET);
        }
        $this->sendResponse($code, $message);
    }

    /**
     * Print json data . Set http headers to application/json
     * @param integer $status HTTP status
     * @param mixed $body content to print
     * @param string $template template to use
     */
    private function sendResponse($status = 200, $body = '') {
        $container = array('api_name' => self::APPLICATION_ID, 'api_version' => self::API_VERSION, 'status' => $status);

        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        header($status_header);
        header('Content-type: ' . 'application/json;charset=UTF-8');


        if (!empty($body)) {
            $container['results'] = $body;
        } else {
            $container['results'] = 'no_results';
        }


        $json_encoded_result = CJSON_Nested::encode($container);

        // serve padded json
        if (isset($_GET['callback']))
            echo $_GET['callback'] . ' (' . $json_encoded_result . ');';
        else
            echo $json_encoded_result;

        die();
    }

    /**
     * Return a human readable version of the HTTP status
     * @param integer $status HTTP status
     * @return string human readable HTTP status
     */
    private function getStatusCodeMessage($status) {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    /**
     * Check that the given api credentials are valid
     * @return boolean return authentification result
     */
    private function checkAuth() {

        if (!isset($_GET['api_key']) || !isset($_GET['api_secret']))
            return false;

        $api_key = Yii::app()->params['api_key'];
        $api_secret = Yii::app()->params['api_secret'];

        return ( $api_key == $_REQUEST['api_key'] && $api_secret == $_REQUEST['api_secret'] );
    }

}