<?php

class ApiController extends Controller {
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */

    const APPLICATION_ID = 'mvghslf';
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

    /*
     * @Description: /api/candidates/
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

            case 'alert_types':
                $alert_types = AlertType::model()->with('tag')->findAll();
                foreach ($alert_types as $alert_type) {
                    $alert_type->tag_id = $alert_type->tag->name;
                }




                $result = $alert_types;
                break;

            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                break;
        }

        $this->_sendResponse(200, $result);
    }

    public function actionView() {
        switch ($_GET['model']) {
            case 'candidates': //api/candidates/state/w{3}/d+
                $this->_sendResponse(200, $this->_getCandidates($_GET));
                break;

            case 'options': //api/options/type/w+
                $this->_sendResponse(200, $this->_getOptions($_GET));
                break;

            case 'tags':
                $this->_sendResponse(200, $this->_getTags($_GET));
                break;

            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                break;
        }
    }

    private function _getCandidates($param) {
        $search_attributes = array();
        if (isset($param['state_abbr']))
            $search_attributes['state_abbr'] = $param['state_abbr'];
        else
            return false;

        if (isset($param['district_number'])) {

            $senator_candidate_district_id = District::getIdByStateAndDistrict($param['state_abbr'], 0);

            $district_id = District::getIdByStateAndDistrict($param['state_abbr'], $param['district_number']);
            $search_attributes['district_id'] = array($district_id, $senator_candidate_district_id);
        }



        $search_attributes['publish'] = 'yes';

        $candidates = Candidate::model()->with('district')->findAllByAttributes($search_attributes);



        foreach ($candidates as $candidate) {
            $candidate->district_id = $candidate->district->number;
        }

        return $candidates;
    }

    private function _getOptions($param) {
        $type_filter = $_GET['type']; //already sanitized in main.php, see regex
        $search_attributes['name'] = $type_filter;
        $filtered_options = Option::model()->findAllByAttributes($search_attributes);

        $tag = new Tag();
        $tag->tag = 'test';
        $tag->type = 'the_type';
        $tag->id = 'name';

        return $tag;

        return $filtered_options;
    }

    private function _getTags($param) {
        $search_attributes['type'] = $_GET['type']; //already sanitized in main.php, see regex
        $filtered_options = Tag::model()->findAllByAttributes($search_attributes);
        return $filtered_options;
    }

    public function actionCreate() {
        if (!$this->_checkAuth()) {
            $this->_sendResponse(401, $this->_getStatusCodeMessage(401));
            return false;
        }
        switch ($_GET['model']) {
            case 'app_users': //insert/update  user record
                error_log(print_r($_REQUEST, true));
                $save_result = $this->_add_applicationUsers();
                if ($save_result == 1) {
                    $this->_sendResponse($status = 200, $body = 'insert_ok');
                } else {
                    $this->_sendResponse($status = 200, $body = 'insert_failed');
                }
                break;

            default:exit;
        }
    }

    public function actionUpdate() {
        if (!$this->_checkAuth()) {
            $this->_sendResponse(401, $this->_getStatusCodeMessage(401));
            return false;
        }

        switch ($_REQUEST['action']) {
            case 'tag':
                $update_result = $this->_update_applicationUserTag($_GET['device_token'], $_POST);
                $this->_sendResponse($status = 200, $update_result);
                break;

            case 'meta':
                $update_result = $this->_update_applicationUserMeta($_GET['device_token'], $_POST);
                $this->_sendResponse($status = 200, $update_result);
                break;

            default:exit;
        }
    }

    private function _add_applicationUsers() {

        $save_result = 0;
        if (!isset($_POST['device_token']) || !isset($_POST['state_abbr']) || !isset($_POST['district_number']) || !isset($_POST['type']) || !isset($_POST['uap_user_id'])) {
            exit;
        }

        $device_token = $_POST['device_token'];
        $user_state = $_POST['state_abbr'];
        $user_district_number = $_POST['district_number'];

        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (!$app_user) { // if user is not already saved in the DB, create a new one
            $app_user = new Application_user();
            $app_user->device_token = $device_token;
        }

        $app_user->uap_user_id = $_POST['uap_user_id'];

        if (isset($_POST['user_lat']) && preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', $_POST['user_lat']) && isset($_POST['user_long']) && preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', $_POST['user_long'])) {
            // lat & long are not mandatory but should only be saved if both are valid.
            $app_user->latitude = $_POST['user_lat'];
            $app_user->longitude = $_POST['user_long'];
        }


        if (preg_match('/^[a-z]{2,3}$/', $_POST['state_abbr'])) { // state abbr input must be between 2 or 3 characters (lowercase)
            $app_user->state_abbr = strtolower($_POST['state_abbr']);
        }
        else
            exit;


        if (preg_match('/^[0-9]{1,}$/', $user_district_number)) { // check that $user_district number is only made of numbers
            $district_id = District::getIdByStateAndDistrict($user_state, $user_district_number);
        }
        else
            exit;


        switch ($_POST['type']) {
            case 'android':
            case 'ios':
                $app_user->type = $_POST['type'];
                break;

            default: error_log('app_user: wrong type given');
                exit;
        }

        $app_user->registration = date('Y-m-d H:i:s');

        //$app_user->updateLocation( $user_state, $user_district_number );
        try {
            if (!$district_id) { // the district isn't saved in the database, insert a new one
                $district = new District;
                $district->state_abbr = $user_state;
                $district->number = $user_district_number;
                $district->save();
                $district_id = $district->id;
            }

            $app_user->district_id = $district_id;
            $save_result = $app_user->save();
        } catch (Exception $exception) {
            error_log('API actionCreate app_users: ' . $exception->getMessage());
        }

        //save user meta after the user is saved/updated
        if (isset($_POST['meta']) && is_array($_POST['meta'])) {
            foreach ($_POST['meta'] as $meta_key => $meta_value) {
                $app_user->updateMeta($meta_key, $meta_value);
            }
        }
        return $save_result;
    }

    private function _update_applicationUserTag($device_token, $payload) {

        if (empty($device_token) || empty($payload['user_id']) || ( empty($payload['add_tags']) && empty($payload['delete_tags']) ))
            return 'missing_parameters';


        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));


        if (empty($app_user))
            return 'no_user_found';

        // delete tags associated to the app user
        if (!empty($payload['delete_tags'])) {
            foreach ($payload['delete_tags'] as $tag) {
                $app_user->deleteTag($tag);
            }
        }

        // add tags associated to the app user
        if (!empty($payload['add_tags'])) {
            foreach ($payload['add_tags'] as $tag) {
                $app_user->addTag($tag);
            }
        }

        if (!empty($payload['state_abbr']) && !empty($payload['district_number'])) {
            $app_user->updateLocation($payload['state_abbr'], $payload['district_number']);
        }

               
        $app_user->synchronizeUAPTags();
        // todo:
        // get all tags for a user ( dont forget to generate tag for location)
        // transmit all those tags to UAP
        return 'tags_updated';
    }

    public function _update_applicationUserMeta($device_token, $payload) {
        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (empty($app_user))
            return 'no_user_found';

        if (isset($_POST['meta']) && is_array($_POST['meta'])) {
            foreach ($_POST['meta'] as $meta_key => $meta_value) {
                $app_user->updateMeta($meta_key, $meta_value);
            }
        }

        return 'meta_updated';
    }

    private function _sendResponse($status = 200, $body = '') {
        $container = array('api_name' => self::APPLICATION_ID, 'api_version' => self::API_VERSION, 'status' => $status);

        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
        header($status_header);
        header('Content-type: ' . 'application/json;charset=UTF-8');


        if (!empty($body)) {
            $container['results'] = $body;
        } else {
            $container['results'] = 'no_results';
        }

        $json_encoded_result = CJSON::encode($container);

        // API consumers really want a district_name, not a district_id 
        $json_encoded_result = str_replace('district_id', 'district_number', $json_encoded_result);

        echo $json_encoded_result;
        exit;
    }

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

    private function _checkAuth() {
        $api_salt = Yii::app()->params['api_salt'];
        $api_username = Yii::app()->params['api_username'];
        $api_password = Yii::app()->params['api_password'];

        if (isset($_POST['user']) and isset($_POST['password'])) {
            return ( ($api_username == $_POST['user']) && (md5($api_password . $api_salt) == md5($_POST['password'] . $api_salt)) );
        } else {
            return false;
        }
    }

}