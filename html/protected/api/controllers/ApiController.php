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
    public function actionList($tenant_id, $model) {
        $this->resolveAction($model, $tenant_id, 'getList', null, $_GET);
    }

    /**
     * List models according to a specific request
     */
    public function actionView($tenant_id, $model, $id) {
        $this->resolveAction($model, $tenant_id, 'getSingle', $id, $_GET);
    }

    /**
     * Handle POST Requests
     */
    public function actionCreate($tenant_id, $model) {
        $this->resolveAction($model, $tenant_id, 'create', null, $_POST);
    }

    /**
     * Handle PUT Requests
     */
    public function actionUpdate($tenant_id, $model, $id) {
        $data = array();

        // retrieve PUT data
        parse_str(file_get_contents("php://input"), $data);

        $this->resolveAction($model, $tenant_id, 'update', $id, $data);
    }

    /**
     * Helpers - call the correct rest model based on the given arguments
     * @param string $requestModelName model name
     * @param integer $tenantId tenant id
     * @param string $actionName action name
     *  @param integer $id id - optionnal
     *  @param array $data extra parameters - optionnal
     * @return array
     */
    private function resolveAction($modelName, $tenantId, $actionName, $id = null, $data = array()) {
        Yii::app()->params['current_tenant_id'] = $tenantId;

        if (( $requestedModel = $this->getRequestedModel($modelName, $tenantId) ) && $requestedModel['model'] != null) {
            $model = $requestedModel['model'];
        } else {
            $this->sendResponse($requestedModel['code'], $requestedModel['message']);
            return;
        }

        unset($data['model']);

        if ($id == null) {
            $result = $model->$actionName($tenantId, $data);
        } else {
            $result = $model->$actionName($tenantId, $id, $data);
        }

        if ($result instanceof RestFailure) {
            $code = $result->getHttpCode();
            $result = $result->getReason();
        } else {
            $code = 200;
        }

        $this->sendResponse($code, $result);
    }

    /**
     * Helpers - return a $requestModelName object 
     * Also set the authentification flag
     * @param string $requestModelName model name
     * @param integer $tenantId tenant id - needed for checking authorization
     * @return array
     */
    private function getRequestedModel($requestModelName, $tenantId) {
        $modelName = $requestModelName . 'API';
        $message = "";
        $code = 200;
        $model = null;

        if (class_exists($modelName)) {
            $model = new $modelName();

            if ($model->requiresAuthentification()) {
                if ($this->checkAuth($tenantId)) {
                    $model = $model;
                } else {
                    $code = 401;
                    $message = 'invalid credentials';
                    $model = null;
                }
            }
        } else {
            $message = 'not found';
            $code = 404;
        }


        return array(
            'model' => $model,
            'code' => $code,
            'message' => $message
        );
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
     * @param integer $tenantId tenant id to check against
     * @return boolean return authentification result
     */
    private function checkAuth($tenantId) {

        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if (!(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))) {
            return false;
        }

        $http_key = $_SERVER['PHP_AUTH_USER'];
        $http_pass = $_SERVER['PHP_AUTH_PW'];

        $tenant = Tenant::model()->findByPk($tenantId);

        if ($tenant == null) {
            return;
        }
        $api_key = $tenant->api_key;

        $api_secret = $tenant->api_secret;

        return ( $api_key == $http_key && $api_secret == $http_pass );
    }

}