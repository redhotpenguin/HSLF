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

            case 'candidate':
                $this->_sendResponse(200, $this->_getCandidate($_GET['id'], $_GET['filter']));
                break;


            case 'options': //api/options/type/w+
                $this->_sendResponse(200, $this->_getOptions($_GET));
                break;

            case 'tags': // /api/tags/type/w+
                $this->_sendResponse(200, $this->_getTags($_GET));
                break;

            case 'ballot_items': //api/ballot_items/w{3}/
                
                if(array_key_exists('ballot_item_id', $_GET)) // return a single ballot item
                    $this->_sendResponse(200, $this->_getBallotItem($_GET['ballot_item_id']));
                else // return multiple ballot items
                    $this->_sendResponse(200, $this->_getBallotItems($_GET));
                break;

            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404));
                break;
        }
    }
    
    private function _getBallotItem($ballot_item_id){
        
        $ballot = BallotItemManager::findByID($ballot_item_id);

        return $ballot;
       
    }

    //ex: /api/ballot_items/state/or/?districts=county/clackamas,city/portland
    private function _getBallotItems($params) {
        $year = $params['year'];

        $state_abbr = $params['state_abbr'];  // already validated by the regex in main.php

        $districts_param = $params['districts']; // #TODO: FILTER THIS
        // if requested, return running ballot items
        // can only be used if the year is set
        if ($params['active'] === 'true' && !empty($year))
            $active = true;
        else
            $active = false;

        $encoded_districts = explode(',', $districts_param);

        // return ballot items by districts
        if ( !empty($districts_param) ) {       
             $district_types = array();
             $districts = array();

            foreach ($encoded_districts as $encoded_district) {
                $d = explode('/', $encoded_district);
                array_push($district_types, $d[0]);
                array_push($districts, $d[1]);
            }
            $ballots = BallotItemManager::findAllByDistricts($state_abbr, $district_types, $districts, $year, $active);

        } 
        // return items by states
        else {
           $ballots = BallotItemManager::findAllByState($state_abbr, $year, $active);
        }


        //$ballots = BallotItemManager::findAllByDistricts($state_abbr, $district_types, $districts, $year, $active);

        return $ballots;
    }

    private function _getCandidates($param) {

        $search_attributes = array();
        if (isset($param['state_abbr']))
            $search_attributes['state_abbr'] = $param['state_abbr'];
        else
            return false;

        if (isset($param['district_number'])) {

            $senator_candidate_district_id = DistrictManager::getDistrictId($param['state_abbr'], 'statewide', 'U.S. Senate'); // this is a temporary fix

            $district_id = DistrictManager::getIdByStateAndDistrict($param['state_abbr'], $param['district_number']);
            $search_attributes['district_id'] = array($district_id, $senator_candidate_district_id);
        }


        $search_attributes['publish'] = 'yes';
        $candidates = Candidate::model()->with('district')->findAllByAttributes($search_attributes);

        foreach ($candidates as $candidate) {
            $candidate->district_id = $candidate->district->number;
        }

        return $candidates;
    }

    private function _getCandidate($candidate_id, $filter) {
        switch ($filter) {
            case 'issue': // /api/candidate/<candidate_id>/issue/
                $candidate_issues = CandidateIssue::model()->findAllByAttributes(array('candidate_id' => $candidate_id));
                $templetized_issues = array();

                foreach ($candidate_issues as $candidate_issue) {
                    ob_start();
                    $this->renderPartial('/api/issue/issue_detail', array('candidate_issue' => $candidate_issue));
                    $detail = ob_get_contents();
                    ob_end_clean();

                    array_push($templetized_issues, array(
                        'name' => $candidate_issue->name,
                        'value' => $candidate_issue->value,
                        'detail' => $detail,
                    ));
                }

                $response = $templetized_issues;
                break;

            default:
                $candidate = Candidate::model()->with('district')->findByPk($candidate_id);
                if (!empty($candidate)) {
                    $candidate->district_id = $candidate->district->number; //return the district number instead of the district id
                    $response = $candidate;
                }
                else
                    $response = 'Candidate not found';
        }

        return $response;
    }

    private function _getOptions($param) {
        $type_filter = $_GET['type']; //already sanitized in main.php, see regex
        $search_attributes['name'] = $type_filter;
        $filtered_options = Option::model()->findAllByAttributes($search_attributes);


        return $filtered_options;
    }

    private function _getTags($param) {
        $search_attributes['type'] = $_GET['type']; //already sanitized in main.php, see regex
        $filtered_options = Tag::model()->findAllByAttributes($search_attributes);
        return $filtered_options;
    }

    public function actionCreate() {
        if (YII_DEBUG)
            error_log(print_r($_REQUEST, true));

        if (!$this->_checkAuth()) {
            $this->_sendResponse(401, $this->_getStatusCodeMessage(401));
            return false;
        }
        switch ($_GET['model']) {
            case 'app_users': //insert/update  user record
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

    public function actionUpdate() {
        if (YII_DEBUG)
            error_log(print_r($_REQUEST, true));

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

    private function _add_applicationUser() {
        if (
                getPost('device_token') == false
                || getPost('state_abbr') == false
                || getPost('district_number') == false
                || getPost('type') == false
                || getPost('uap_user_id') == false
        ) {
            return 'missing_parameter';
        }

        $save_result = 0;

        $device_token = getPost('device_token');
        $user_state = getPost('state_abbr');
        $user_district_number = getPost('district_number');
        $user_district_type = getPost('district_type');

        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (!$app_user) { // if user is not already saved in the DB, create a new one
            $app_user = new Application_user();
            $app_user->device_token = $device_token;
            $app_user->registration = date('Y-m-d H:i:s');
        }

        $app_user->uap_user_id = getPost('uap_user_id');

        if (isPost('user_lat') && preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', getPost('user_lat')) && preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', getPost('user_long'))) {
            // lat & long are not mandatory but should only be saved if both are valid.
            $app_user->latitude = getPost('user_lat');
            $app_user->longitude = getPost('user_long');
        }


        if (!District::isValidDistrictType($user_district_type))
            return 'invalid_district_type';

        if (preg_match('/^[0-9]{1,}$/', $user_district_number)) { // check that $user_district number is only made of numbers
            $district_id = DistrictManager::getDistrictId($user_state, $user_district_type, $user_district_number);
        }
        else
            return 'invalid_district';

        if (in_array(getPost('type'), array('android', 'ios', 'blackberry')))
            $app_user->type = getPost('type');
        else
            return 'invalid_device_type';

        try {
            if (!$district_id) { // the district isn't saved in the database, insert a new one
                $district = new District;
                $district->state_abbr = $user_state;
                $district->number = $user_district_number;
                $district->type = $user_district_type;
                $district->save();
                $district_id = $district->id;
            }
            $app_user->district_id = $district_id;
            $save_result = $app_user->save();
        } catch (Exception $exception) {
            error_log('API: user registration error: ' . $exception->getMessage());
            return 'insert_failed';
        }

        //save user meta after the user is saved/updated
        if (isPost('meta') && is_array(getPost('meta'))) {
            foreach (getPost('meta') as $meta_key => $meta_value) {
                $app_user->updateMeta($meta_key, $meta_value);
            }
        }


        return $save_result;
    }

    private function _update_applicationUserTag($device_token, $payload) {

        if (empty($device_token) || ( empty($payload['add_tags']) && empty($payload['delete_tags']) ))
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

        $app_user->synchronizeUAPTags();
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
        $api_key = Yii::app()->params['api_key'];
        $api_pass = Yii::app()->params['api_secret'];

        return ( $api_key == $_POST['api_key'] && $api_pass == $_POST['api_secret'] );
    }

}