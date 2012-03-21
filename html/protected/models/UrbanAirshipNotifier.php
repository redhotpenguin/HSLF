<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class UrbanAirshipNotifier extends CModel{

    public function attributeNames() {
        return array(  );
    }
    
    public static function send_push_notifications($application_users, $message ) {
            $android_apids = array();
            $ios_device_tokens = array();
            $ios_push_result = false;
            $android_push_result = false;
     
            foreach($application_users as $application_user){
                if($application_user->type == 'ios'){
                   array_push($ios_device_tokens, $application_user->device_token);
                }
                elseif($application_user->type == 'android'){
                   array_push($android_apids, $application_user->device_token);
                }
                else{
                    continue;
                }      
            }
            
            
           $airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);
           
    
          if( !empty($ios_device_tokens) ){
            $ios_payload['device_tokens'] = $ios_device_tokens;
            $ios_payload['aps'] = array(
                    'badge'=>'the badge',
                    'alert' => $message,
                    'sound' => 'whut'
            );
             $ios_push_result = $airship->batch_push($ios_payload);
            }
            
         if( !empty($android_apids) ){
            $android_payload['apids'] = $android_apids;
            $android_payload['android'] = array(
                'alert' => $message,
                'extra' => 'extra'
            );
            $android_push_result = $airship->batch_push($android_payload);
          }
          
         
          if( (!empty($ios_device_tokens) && $ios_push_result == false) || (!empty( $android_apids) && $android_push_result == false) ){
              return false;
          }
          
          else{
              return true;
          }

   
       

    }
}

?>
