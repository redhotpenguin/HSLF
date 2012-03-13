<?php 
class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers 
     */
    const APPLICATION_ID = 'mvghslf';
    const API_VERSION = '0.1';
 
    
    public function actionIndex(){
    	$this->_sendResponse(500 );
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
            return array();
    }
    
    public function actionList() {
        $result = '';
        switch($_GET['model']){
           
            case 'candidates' :
            // list ALL candidates
             //$candidates = Candidate::model()->findAll();
             
             case 'alerts':
                 if( isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0  )
                     $limit = $_GET['limit'];
                else
                    $limit = 10;
                
                $district_id = District::getIdByStateAndDistrict('na', '0');
                
 
                $attributes = array(
                    'state_abbr'=>'na',
                    'district_id'=> $district_id,
                   );
                
                 $params = array(
                     'limit' => $limit,
                     'order' => 'id DESC',
                 );
         
                 $alerts = User_alert::model()->findAllByAttributes($attributes, $params );
                 $result = $alerts;
             break;

            
             default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404) );
                break;
            
        }
        
        $this->_sendResponse(200, $result);
    }
    
     public function actionView() {
         switch( $_GET['model'] ){
             case 'candidates':
                $this->_sendResponse(200, $this->_getCandidates($_GET) ); 
                break;
             
            case 'alerts':
                $this->_sendResponse(200, $this->_getAlerts($_GET) ); 
                break;
            
            default:
                $this->_sendResponse(404, $this->_getStatusCodeMessage(404) );
                break;
             
         }
    }
    

 
    
    private function _getCandidates($param){
        $search_attributes = array();
        
        if(isset($param['state_abbr']))
            $search_attributes['state_abbr'] = $param['state_abbr'];
         else
             return false;
        
        if( isset($param['district_number']) ){
            $district_id = District::getIdByStateAndDistrict($param['state_abbr'], $param['district_number']);
 
           $search_attributes['district_id'] = $district_id; 
        }
       
      
        $search_attributes['publish'] = 'yes';

        $candidates = Candidate::model()->with('district')->findAllByAttributes($search_attributes);

        foreach($candidates as $candidate) {
            $candidate->district_id =  $candidate->district->number;
        }
        


        return $candidates;

         }
 
 
    private function _getAlerts($param){
       $search_attributes = array();
        
        if(isset($param['state_abbr'])){
            $search_attributes['state_abbr'] =  array($param['state_abbr'], 'na');
        }
         else
             return false;
        
        if( isset($param['district_number']) ){
             $district_id = District::getIdByStateAndDistrict($param['state_abbr'],$param['district_number']);
             $search_attributes['district_id'] = array($district_id, 8);
             
        }
    
       $alerts =  User_alert::model()->with('district')->findAllByAttributes($search_attributes);
       foreach($alerts as $alert)
           $alert->district_id = $alert->district->number;
       
       return $alerts;
    }
    
    public function actionCreate(){
       if( !$this->_checkAuth() ){
          $this->_sendResponse(401, $this->_getStatusCodeMessage(401) );
           return false;
       }
 
        switch($_GET['model']){
            case 'app_users': //insert/update  user record
                $device_token = $_POST['device_token'];
                $user_lat = $_POST['user_lat'];
                $user_long = $_POST['user_long'];
                $user_state = $_POST['state_abbr'];
                $district_number = $_POST['district_number'];
                $user_type = $_POST['type']; 

                $model = Application_users::model()->findByAttributes(array('device_token'=>$device_token));

                $save_result = 0;
                if(!$model){
                    $model = new Application_users();
                    $model->device_token= $device_token;
                }
                
                $model->latitude = $user_lat;
                $model->longitude = $user_long;
                $model->state_abbr = $user_state;
                $model->district = District::getIdByStateAndDistrict($user_state, $district_number);
                $model->type = $user_type;
 
                try{
                $save_result = $model->save();
                }
                catch(Exception $exception){
                     error_log('API actionCreate app_users: '.$exception->getMessage() );
                }

                if($save_result == 1){
                    $this->_sendResponse($status = 200, $body = 'insert_ok');
                }
                else{
                    $this->_sendResponse($status = 200, $body = 'insert_failed');
                }
   
                break;
            
             default:exit;
            
        }
    }
        
    private function _sendResponse($status = 200, $body = '')
    {
    	$container = array( 'api_name' => self::APPLICATION_ID, 'api_version' => self::API_VERSION, 'status'=> $status );
       
    	$status_header = 'HTTP/1.1 ' . $status . ' ' . $this->_getStatusCodeMessage($status);
    	header($status_header);
    	header('Content-type: ' . 'application/json');

    	if( !empty($body) ) {
            $container['results'] = $body;       
    	}
 
    	else{
            $container['results'] = 'no_results';
    	}
        
        echo  CJSON::encode($container);
        exit;
    }
    
    private function _getStatusCodeMessage($status)
    {
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
    
    private function _checkAuth(){
        $api_salt = Yii::app()->params['api_salt'];
        $api_username = Yii::app()->params['api_username'];
        $api_password = Yii::app()->params['api_password'];
        
        
        if( !(isset($_POST['HTTP_X_USERNAME']) and isset($_POST['HTTP_X_PASSWORD']) )) {
             return true;
        }

        return ( ($api_username == $_POST['HTTP_X_USERNAME']) && (md5($api_password.$api_salt)  ==  md5( $_POST['HTTP_X_PASSWORD'].$api_salt)) );
    }

}

