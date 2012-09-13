<?php

// Yii::import('application.vendors.*');
//require_once('urbanairship/urbanairship.php');
//require_once('urbanairship/rich_urbanairship.php');

class UrbanAirshipNotifier extends CModel {

    private $airship;
    private $rich_airship;

    public function __construct() {
      //  $this->airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);
        $this->rich_airship = new Rich_Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);
    }

    public function attributeNames() {
        return array();
    }

    /**
     * Request a notification to specifiesd application_users
     * @param string $message the alert message
     * @param array of tags
     * @return true if the notification worked, else return false
     */
    public function sendPushNotifications($alert, array $tags) {


        try {
            $result = $this->airship->push_to_tags($alert, $tags);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }

        return $result;
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


        $ios_payload = array(
            'aps' => array('alert' => $message)
        );

        $response_ios_broadcast = $this->airship->broadcast_ios($ios_payload);
        $response_android_broadcast = $this->airship->broadcast_android($message);


        if ($response_ios_broadcast == 200) {
            $broadcast_result['BROADCAST_IOS'] = true;
        }

        if ($response_android_broadcast == 200) {
            $broadcast_result['BROADCAST_ANDROID'] = true;
        }

        return $broadcast_result;
    }

    public function delete_device_tag($tag, $device_token, $device_type) {

        try {
            $r = $this->airship->delete_device_tag($tag, $device_token, $device_type);
        } catch (Excetpion $e) {
            error_log('UrbanAIrshipNotifier error deleting tag: ' . $e->getMessage());
        }

        return $r;
    }

    public function add_device_tag($tag, $device_token, $device_type) {
        try {
            $r = $this->airship->add_device_tag($tag, $device_token, $device_type);
        } catch (Excetpion $e) {
            error_log('UrbanAIrshipNotifier error adding tag ' . $tag . ': ' . $e->getMessage());
        }

        return $r;
    }

    public function updateRichUserTags($uap_user_id, $device_token, array $tags) {
        try {
            $r = $this->rich_airship->update_device_tags($tags, $device_token, $uap_user_id, 'ios');
        } catch (CExcetpion $ce) {
            error_log('UrbanAIrshipNotifier error updating user tags: ' . $ce->getMessage());
        }

        return $r;
    }

}

?>
