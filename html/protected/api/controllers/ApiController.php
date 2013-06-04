<?php

class ApiController extends Controller {

    const APPLICATION_ID = 'MOBILE API';
    const API_VERSION = '2.0';

    public function actionIndex() {
        $this->sendResponse(404);
    }

    public function actionError() {
        $this->sendResponse(503);
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
        $result = $this->resolveAction('GET', $model, $tenant_id, 'getList', null, $_GET);
        $this->sendResponse($result['httpCode'], $result['data']);
    }

    /**
     * List models according to a specific request
     */
    public function actionView($tenant_id, $model, $id) {
        $result = $this->resolveAction('GET', $model, $tenant_id, 'getSingle', $id, $_GET);
        $this->sendResponse($result['httpCode'], $result['data']);
    }

    /**
     * Handle POST Requests
     */
    public function actionCreate($tenant_id, $model) {
        $result = $this->resolveAction('POST', $model, $tenant_id, 'create', null, $_POST);
        $this->sendResponse($result['httpCode'], $result['data']);
    }

    /**
     * Handle PUT Requests
     */
    public function actionUpdate($tenant_id, $model, $id) {
        $data = array();

        // retrieve PUT data
        parse_str(file_get_contents("php://input"), $data);

        $result = $this->resolveAction('PUT', $model, $tenant_id, 'update', $id, $data);
        $this->sendResponse($result['httpCode'], $result['data']);
    }

    /**
     * @todo - REFACTOR THIS CRAP
     * Helpers - call the correct rest model based on the given arguments
     * @param string $requestModelName model name
     * @param integer $tenantId tenant id
     * @param string $actionName action name
     * @param integer $id id - optionnal
     * @param array $data extra parameters - optionnal
     * @return array containg an http code and the json result
     */
    private function resolveAction($method, $modelName, $tenantId, $actionName, $id = null, $data = array()) {
        $cacheKey = $_SERVER['REQUEST_URI'];
        $cachable = false;


        Yii::app()->params['current_tenant_id'] = $tenantId;

        if (( $requestedModel = $this->getRequestedModel($modelName, $tenantId) ) && $requestedModel['model'] != null) {
            $model = $requestedModel['model'];
        } else {
            $this->sendResponse($requestedModel['code'], $requestedModel['message']);
            return;
        }

        unset($data['model']);

        if ($method === 'GET' && $model->getCacheDuration() > 0) {
            $cachable = true;
            if (($cachedJsonResult = Yii::app()->cache->get($cacheKey)) == true) {
                $this->sendResponse(200, $cachedJsonResult);
            }
        }


        if ($id == null) {
            $result = $model->$actionName($tenantId, $data);
        } else {
            $result = $model->$actionName($tenantId, $id, $data);
        }

        if ($result instanceof RestFailure) {
            $code = $result->getHttpCode();
            $jsonData = $this->buildResponse($code, $result->getReason());
        } else {
            $code = 200;
            $jsonData = $this->buildResponse($code, $result);

            if ($cachable) {
                Yii::app()->cache->set($cacheKey, $jsonData, $model->getCacheDuration());
            }
        }


        return array(
            'httpCode' => $code,
            'data' => $jsonData
        );
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
            $model = new $modelName($tenantId);

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

    private function buildResponse($status, $body = '') {
        $container = array('api_name' => self::APPLICATION_ID, 'api_version' => self::API_VERSION, 'status' => $status);

        if (!empty($body)) {
            $container['results'] = $body;
        } else {
            $container['results'] = 'no_results';
        }

        $json_encoded_result = CJSON_Nested::encode($container);

        // serve padded json
        if (isset($_GET['callback']))
            return $_GET['callback'] . ' (' . $json_encoded_result . ');';
        else
            return $json_encoded_result;
    }

    /**
     * Print json data . Set http headers to application/json
     * @param integer $status HTTP status
     * @param mixed $body content to print
     * @param string $template template to use
     */
    private function sendResponse($status = 200, $jsonData) {
        header('HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status));
        header('Content-type: ' . 'application/json;charset=UTF-8');


        if ($status == 503) {
            header('Retry-After: 60');
        }

        echo $jsonData;

        Yii::app()->end();
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

        $cacheKey = 'tenant_' . $tenantId;

        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            $tenantInfo = $r;
        } else {
            // only query the columns we actually care for (return an array)
            $tenantInfo = Yii::app()->db->createCommand()
                    ->select('api_key, api_secret')
                    ->from('tenant')
                    ->where('id=:id', array(':id' => $tenantId))
                    ->limit(1)
                    ->queryRow();
            Yii::app()->cache->set($cacheKey, $tenantInfo, Yii::app()->params->long_cache_duration);
        }

        if ($tenantInfo == null) {
            return;
        }
        $api_key = $tenantInfo['api_key'];

        $api_secret = $tenantInfo['api_secret'];

        return ( $api_key == $http_key && $api_secret == $http_pass );
    }

}