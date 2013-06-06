<?php

class ApiController extends Controller {

    const APPLICATION_ID = 'MOBILE API';
    const API_VERSION = '2.0';

    private $cacheKey;

    public function __construct() {
        $this->cacheKey = $_SERVER['REQUEST_URI'];
    }

    public function actionIndex() {
        $this->sendResponse(404, $this->buildResponse(404));
    }

    public function actionError($httpCode = 503, $message = "") {
        $this->sendResponse($httpCode, $this->buildResponse($httpCode, $message));
    }

    /**
     * @return array action filters
     */
    public function filters() {
        return array();
    }

    /**
     * Retrieve all resources (READ)
     */
    public function actionList($tenantId, $resource) {
        $this->setTenantId($tenantId);

        $model = $this->getVerifiedModel($tenantId, $resource);

        if ($model->getCacheDuration() > 0) {
            $cachable = true;
            if (($cachedJsonResult = Yii::app()->cache->get($this->cacheKey)) == true) {
                $this->sendResponse(200, $cachedJsonResult);
            }
        }


        try {
            $result = $model->getList($tenantId, $_GET);

            $code = 200;
            $jsonData = $this->buildResponse($code, $result);

            if ($cachable && !empty($result)) {
                Yii::app()->cache->set($this->cacheKey, $jsonData, $model->getCacheDuration());
            }
        } catch (Exception $e) {
            $code = $e->getCode();
            $jsonData = $this->buildResponse($code, $e->getMessage());
        }


        $this->sendResponse($code, $jsonData);
    }

    /**
     * Retrieve a resource by ID (READ)
     */
    public function actionView($tenantId, $resource, $id) {
        $this->setTenantId($tenantId);

        $model = $this->getVerifiedModel($tenantId, $resource);

        if ($model->getCacheDuration() > 0) {
            $cachable = true;
            if (($cachedJsonResult = Yii::app()->cache->get($this->cacheKey)) == true) {
                $this->sendResponse(200, $cachedJsonResult);
            }
        }

        try {
            $code = 200;
            $result = $model->getSingle($tenantId, $id, $_GET);

            $jsonData = $this->buildResponse($code, $result);

            if ($cachable && !empty($result)) {
                Yii::app()->cache->set($this->cacheKey, $jsonData, $model->getCacheDuration());
            }
        } catch (Exception $e) {
            $code = $e->getCode();
            $jsonData = $this->buildResponse($code, $e->getMessage());
        }

        $this->sendResponse($code, $jsonData);
    }

    /**
     * Handle POST Requests (CREATE)
     */
    public function actionCreate($tenantId, $resource) {
        $this->setTenantId($tenantId);

        $model = $this->getVerifiedModel($tenantId, $resource);

        try {
            $code = 200;
            $result = $model->create($tenantId, $_POST);

            $jsonData = $this->buildResponse($code, $result);
        } catch (Exception $e) {
            $code = $e->getCode();
            $jsonData = $this->buildResponse($code, $e->getMessage());
        }

        $this->sendResponse($code, $jsonData);
    }

    /**
     * Handle PUT Requests (UPDATE)
     */
    public function actionUpdate($tenantId, $resource, $id) {
        $data = array();

        // retrieve PUT data
        parse_str(file_get_contents("php://input"), $data);

        $this->setTenantId($tenantId);

        $model = $this->getVerifiedModel($tenantId, $resource);

        try {
            $code = 200;
            $result = $model->update($tenantId, $id, $data);
            $jsonData = $this->buildResponse($code, $result);
        } catch (Exception $e) {
            $code = $e->getCode();
            $jsonData = $this->buildResponse($code, $e->getMessage());
        }

        $this->sendResponse($code, $jsonData);
    }

    /**
     * setTenant 'session' tenant ID 
     * @param $tenantId
     */
    private function setTenantId($tenantId) {
        Yii::app()->params['current_tenant_id'] = $tenantId;
    }

    /**
     * Retrieve and verify that $resource corresospond to a  Rest Model
     * also check authorization
     * @param $tenantId 
     * @param $resource
     * @return Rest verified API model or false
     */
    private function getVerifiedModel($tenantId, $resource) {
        $modelName = $resource . 'API';
        if (!class_exists($modelName)) {
            return false;
        }

        $model = new $modelName();

        if (!$model) {
            $this->actionError(404, 'Resource not found');
        }

        if ($model->requiresAuthentification()) {
            if (!$this->checkAuth($tenantId)) {
                $this->actionError(401, 'invalid credentials');
            }
        }

        return $model;
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
    private function sendResponse($status, $jsonData) {
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