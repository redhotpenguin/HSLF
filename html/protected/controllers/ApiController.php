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
                 $alerts = UserAlerts::model()->findAllByAttributes(array('state_abbr'=>'na', 'district_number'=>0));
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
        
        if( isset($param['district_number']) )
             $search_attributes['district_number'] = $param['district_number'];
       
        $search_attributes['publish'] = 'yes';
        
        return Candidate::model()->findAllByAttributes( $search_attributes );
       }
 
   
    private function _getAlerts($param){
       $search_attributes = array();
        
        if(isset($param['state_abbr']))
            $search_attributes['state_abbr'] = $param['state_abbr'];
         else
             return false;
        
        if( isset($param['district_number']) )
             $search_attributes['district_number'] = $param['district_number'];
       
       return UserAlerts::model()->findAllByAttributes($search_attributes);
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
                $user_district = $_POST['district_number'];
                
                
                $app_user = new AppUsers();

                $model = AppUsers::model()->findByPk($device_token);
                $save_result = 0;
                if($model){
                    $model->latitude = $user_lat;
                    $model->longitude = $user_long;
                    $model->state_abbr = $user_state;
                    $model->district_number = $user_district;
                    $save_result = $model->save();
             
                }
                else{
                  
                    $app_user->device_token= $device_token;
                    $app_user->latitude = $user_lat;
                    $app_user->longitude = $user_long;
                    $app_user->state_abbr = $user_state;
                    $app_user->district_number = $user_district;
                
          
                    
                    echo $save_result = $app_user->save();
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

