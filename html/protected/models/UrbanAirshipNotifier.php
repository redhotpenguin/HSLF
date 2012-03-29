<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

class UrbanAirshipNotifier extends CModel {

    public function attributeNames() {
        return array();
    }

    /**
     * Notify application users in specified districts.
     * @param array $district_ids the district ids
     * @param string $message the alert message
     * @return mixed number of notification sent or false
     */
    public function notify_district_users($district_ids, $message) {
        if (empty($district_ids) || empty($message)) {
            return 'INVALID_PARAMETERS';
        }

        $criteria = new CDbCriteria();
        $criteria->addInCondition("district", $district_ids);
        $application_users = Application_users::model()->findAll($criteria);
        $application_user_count = count($application_users);

        if (count($application_users) <= 0) {
            return 'NO_USER_FOUND';
        }

        $notification_result = $this->sendPushNotifications($application_users, $message);

        if ($notification_result['PUSH_IOS'] != false && $notification_result['PUSH_ANDROID'] != false) {
            return $application_user_count;
        } else {
            return false;
        }
    }

    /**
     * Request a notification to specifiesd application_users
     * @param array of Application_users
     * @param string $message the alert message
     * @return array PUSH_IOS AND PUSH_ANDROID. 0, 1 or -1
     */
    public function sendPushNotifications($application_users, $message) {
        $android_apids = array();
        $ios_device_tokens = array();
        $ios_push_result = false;
        $android_push_result = false;
        $results = array(
            'PUSH_IOS' => -1,
            'PUSH_ANDROID' => -1
        );

        // browse $application_users and populate an array of device_tokens and an array of apids
        foreach ($application_users as $application_user) {
            if ($application_user->type == 'ios') {
                array_push($ios_device_tokens, $application_user->device_token);
            } elseif ($application_user->type == 'android') {
                array_push($android_apids, $application_user->device_token);
            } else {
                continue;
            }
        }

        $airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);


        // handle push notification for ios users
        if (!empty($ios_device_tokens)) {
            $ios_payload['aps'] = array(
                'alert' => $message
            );

            try {
                $ios_push_result = $airship->push_ios($ios_payload, $ios_device_tokens);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }


        // handle push notification for android users
        if (!empty($android_apids)) {
            try {
                $android_push_result = $airship->push_android($message, $android_apids);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }


        if (!empty($ios_device_tokens) && $ios_push_result == 200) {
            $results['PUSH_IOS'] = true;
        } elseif (!empty($ios_push_result)) {
            $results['PUSH_IOS'] = false;
        }

        if (!empty($android_apids) && $android_push_result == 200) {
            $results['PUSH_ANDROID'] = true;
        } elseif (!empty($android_apids)) {
            $results['PUSH_ANDROID'] = false;
        }

        return $results;
    }

    /**
     * Request a broadcast notification.
     * @param string $message the alert message
     * @return true or false
     */
    public function sendBroadcastNotification($message) {
        $broadcast_result = array(
            'BROADCAST_IOS' => false,
            'BROADCAST_ANDROID' => false
        );

        $airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);

        $ios_payload = array(
            'aps' => array('alert' => $message)
        );

        $response_ios_broadcast = $airship->broadcast_ios($ios_payload);
        $response_android_broadcast = $airship->broadcast_android($message);


        if ($response_ios_broadcast == 200) {
            $broadcast_result['BROADCAST_IOS'] = true;  
        } 
       
        if($response_android_broadcast == 200){
            $broadcast_result['BROADCAST_ANDROID'] = true;
        }
        
        return $broadcast_result;
       
    }

}

?>
