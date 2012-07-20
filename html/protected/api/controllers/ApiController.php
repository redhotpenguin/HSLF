<?php

class ApiController extends Controller {
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */

    const APPLICATION_ID = 'MOBILE API';
    const API_VERSION = '0.1';

    public function actionIndex() {
        $this->_sendResponse(500);
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
    public function actionList() {
        $result = '';
        switch ($_GET['model']) {
            case 'options': // /api/options
                $result = Option::model()->findAll();
                break;

            case 'tags': // /api/tags
                $result = Tag::model()->findAll();
                break;

            case 'alert_types': // /api/alert_types
                $alert_types = AlertType::model()->with('tag')->findAll();
                $result = $alert_types;
                break;

            case 'ballot_items': // /api/ballot/items
                $result = $this->browseBallotItems();
                break;
            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                break;
        }

        $this->_sendResponse(200, $result);
    }

    /**
     * List models according to a specific request
     */
    public function actionView() {
        switch ($_GET['model']) {
            case 'options': //api/options/type/w+
                $this->_sendResponse(200, $this->_getOptions($_GET));
                break;

            case 'tags': // /api/tags/type/w+
                $this->_sendResponse(200, $this->_getTags($_GET));
                break;

            case 'ballot_items': //api/ballot_items/w{3}/

                if (array_key_exists('ballot_item_id', $_GET)) // return a single ballot item
                    $this->_sendResponse(200, $this->_getBallotItem($_GET['ballot_item_id']));
                else // return multiple ballot items
                    $this->_sendResponse(200, $this->_getBallotItems($_GET));
                break;

            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                break;
        }
    }

    /**
     * return a single ballot item
     * @param $ballot_item_id id of the ballot item
     * @return object return a ballot item object
     */
    private function _getBallotItem($ballot_item_id) {

        $ballot = BallotItemManager::findByID($ballot_item_id);
        if (!empty($ballot))
            return $ballot;
        else
            $this->_sendResponse(404, "no_ballot_found");
    }

    /**
     * return multiple  ballot items
     * @param $params array of parameters: see api documentation
     * @return array of ballot items
     */
    private function _getBallotItems($params) {
        //ex: /api/ballot_items/state/or/?districts=county/clackamas,city/portland

        $year = $params['year'];

        $state_abbr = $params['state_abbr'];  // already validated by the regex in main.php

        $districts_param = $params['districts']; // #TODO: FILTER THIS

        $encoded_districts = explode(',', $districts_param);

        // return ballot items by districts
        if (!empty($districts_param)) {
            $district_types = array();
            $districts = array();

            foreach ($encoded_districts as $encoded_district) {
                $d = explode('/', $encoded_district);
                array_push($district_types, $d[0]);
                array_push($districts, $d[1]);
            }
            $ballots = BallotItemManager::findAllByDistricts($state_abbr, $district_types, $districts, $year);
        }
        // return items by states
        else {
            $ballots = BallotItemManager::findAllByState($state_abbr, $year);
        }


        return $ballots;
    }

   

    /**
     * return a list of options
     * @param $param array of parameters: see api documentation
     * @return  array of option objects
     */
    private function _getOptions($param) {
        $type_filter = $_GET['type']; //already sanitized in main.php, see regex
        $search_attributes['name'] = $type_filter;
        $filtered_options = Option::model()->findAllByAttributes($search_attributes);


        return $filtered_options;
    }

    /**
     * return a list of tags
     * @param $param array of parameters: see api documentation
     * @return array of tag objects
     */
    private function _getTags($param) {
        $search_attributes['type'] = $_GET['type']; //already sanitized in main.php, see regex
        $filtered_options = Tag::model()->findAllByAttributes($search_attributes);
        return $filtered_options;
    }

    /**
     * create object -  handle POST requests on supported models
     * @return string result
     */
    public function actionCreate() {
        if (YII_DEBUG) {
            error_log("user registration:");
            error_log(print_r($_REQUEST, true));
        }

        if (!$this->_checkAuth()) {
            $this->_sendResponse(401, $this->_getStatusCodeMessage(401));
            return false;
        }
        switch ($_GET['model']) {
            case 'app_users': //insert  user record
                $save_result = $this->_add_applicationUser();
                if ($save_result == 1) {
                    $this->_sendResponse(200, 'insert_ok');
                } else {
                    $this->_sendResponse(200, $save_result);
                }
                break;

            default:exit;
        }
    }

    /**
     * update object -  handle POST requests on supported models
     * @return string result
     */
    public function actionUpdate() {
        if (YII_DEBUG) {
            error_Log("tag update:");
            error_log(print_r($_REQUEST, true));
        }

        if (!$this->_checkAuth()) {
            $this->_sendResponse(401, $this->_getStatusCodeMessage(401));
            return false;
        }

        switch ($_REQUEST['action']) {
            case 'tag': // /api/app_user/device_token/<device_token>/tag/
                $update_result = $this->_update_applicationUserTag($_GET['device_token'], $_POST);
                $this->_sendResponse($status = 200, $update_result);
                break;

            case 'meta': // /api/app_user/device_token/<device_token>/meta/
                $update_result = $this->_update_applicationUserMeta($_GET['device_token'], $_POST);
                $this->_sendResponse($status = 200, $update_result);
                break;

            default:exit;
        }
    }

    /**
     * search API entry point
     * @return array of searched objects
     */
    public function actionSearch() {
        $model = getParam('model');
        $search_condition = array();
        switch ($model) {
            case 'ballot_items':
                $search_condition = array();
                break;
            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                return false;
                break;
        }


        $apiSearch = new APISearch($ar_model);
        $search_result = $apiSearch->search('BallotItem', getParam('query'));
        $this->_sendResponse(200, $search_result);
    }

    /**
     * create an application user object (see api doc for parameter descriptions)
     * @return string result
     */
    private function _add_applicationUser() {
        $device_token = getPost('device_token');
        $state_abbr = getPost('state_abbr');
        $district_type = getPost('district_type');
        $district = getPost('district_number');

        $device_type = getPost('type');
        $uap_user_id = getPost('uap_user_id');

        $optional = array(); // parameters that are not required
        $optional['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        if (
                $device_token == null
                || $state_abbr == null
                || $district_type == null
                || $district == null
                || $device_type == null
                || $uap_user_id == null
        ) {
            return 'missing_parameter';
        }

        $api = new RestAPI();

        if (isPost('user_lat') && isPost('user_long')) {
            $optional['geolocation'] = array(
                'lat' => getPost('user_lat'),
                'long' => getPost('user_long')
            );
        }

        if (isPost('meta')) {
            $optional['meta'] = getPost('meta');
        }

        $register_user_result = $api->registerApplicationUser($device_token, $uap_user_id, $device_type, $state_abbr, $district_type, $district, $optional);

        return $register_user_result;
    }

    /**
     * update tags of an application user (see api doc for parameter descriptions)
     * @return string result
     */
    private function _update_applicationUserTag($device_token, $payload) {
        if (empty($device_token))
            return 'missing_parameters';

        $api = new RestAPI();

        if (isset($payload['state_abbr']) && isset($payload['district_type']) && isset($payload['district'])) {
            $district_id = $api->getDistrictIDCreateIfNotExist($payload['state_abbr'], $payload['district_type'], $payload['district']);
        }

        $tags = array(
            'add_tags' => $payload['add_tags'],
            'delete_tags' => $payload['delete_tags']
        );

        return $api->updateApplicationUserTags($device_token, $tags, $district_id);
    }

    /**
     * update user meta data of an application user (see api doc for parameter descriptions)
     * @return string result
     */
    public function _update_applicationUserMeta($device_token, $payload) {
        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));
        if (empty($app_user))
            return 'no_user_found';

        $user_meta_update = $app_user->updateMassMeta($payload['meta']);
        if ($user_meta_update)
            return 'meta_updated';
        else
            return 'error';
    }

    /**
     * Print json data . Set http headers to application/json
     * @param integer $status HTTP status
     * @param mixed $body content to print
     * @param string $template template to use
     */
    private function _sendResponse($status = 200, $body = '', $template = '') {
        $container = array('api_name' => self::APPLICATION_ID, 'api_version' => self::API_VERSION, 'status' => $status);

        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        header('Content-type: ' . 'application/json;charset=UTF-8');


        if (!empty($body)) {
            $container['results'] = $body;
        } else {
            $container['results'] = 'no_results';
        }

        if ($template != '') {
            $this->renderPartial('issue', array('data' => $container));
        } else {

            $json_encoded_result = CJSON_Nested::encode($container);

            echo $json_encoded_result;
        }
        exit;
    }

    /**
     * Return a human readable version of the HTTP status
     * @param integer $status HTTP status
     * @return string human readable HTTP status
     */
    private function _getStatusCodeMessage($status) {
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
    private function _checkAuth() {
        $api_key = Yii::app()->params['api_key'];
        $api_pass = Yii::app()->params['api_secret'];

        return ( $api_key == $_POST['api_key'] && $api_pass == $_POST['api_secret'] );
    }

    /**
     * return an array of ballot item ids and district 
     * filtered by election date and by publication status
     * @return array payload
     */
    private function browseBallotItems() {

        $ballot_items = Yii::app()->db->createCommand()
                ->select('b.id, item, item_type, d.type, d.state_abbr, d.number, d.display_name')
                ->from('ballot_item b')
                ->join('district d', 'b.district_id=d.id')
                ->where('published=:published AND next_election_date>=:current_date', array(
                    ':published' => 'yes',
                    ':current_date' => date('Y-m-d'), // use NOW() instead?
                ))
                ->order('d.state_abbr ASC')
                ->queryAll();

        return $ballot_items;
    }

}