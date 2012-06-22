<?php

/**
 *  Business Logics for the Appication_user model
 *
 * @author jonas
 */
class ApplicationUserManager {

    /**
     * Create a new application user
     * @param string $device_token device token
     * @param string $uap_user_ud urban airship user id
     * @param string $type device type
     * @param integer $district_id district_id
     * @param array $optionnal associative array of optional data: meta or tags
     * @return boolean return true if app user is saved.
     */
    public static function addApplicationUser($device_token, $uap_user_id, $type, $district_id, array $optional = null) {
        $save_result = 0;

        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if ($app_user) { // only accept new registration!
            if (YII_DEBUG)
                error_log("user already exist: $device_token");
            return 'user_already_registered';
        }

        $app_user = new Application_user();
        $app_user->device_token = $device_token;
        $app_user->registration = date('Y-m-d H:i:s');

        $app_user->uap_user_id = $uap_user_id;

        if (in_array($type, array('android', 'ios', 'blackberry')))
            $app_user->type = 'type';
        else
            return 'invalid_device_type';

        if (key_exists('user_agent', $optional))
            $app_user->user_agent = $optional['user_agent'];

        if (key_exists('meta', $optional))
            self::updateApplicationUserMetaByDeviceToken($device_token, $optional['meta']);


        $app_user->district_id = $district_id;

        $save_result = $app_user->save();

        return $save_result;
    }

    /**
     * Update an extisting application user meta data using a device token as an identifier
     * @param string $device_token device token
     * @param array $user_meta associative array of meta data
     * @return boolean return true if app user meta is saved.
     */
    public static function updateApplicationUserMetaByDeviceToken($device_token, array $user_meta) {
        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (empty($app_user) || empty($user_meta))
            return false;

        foreach ($user_meta as $meta_key => $meta_value) {
            $app_user->updateMeta($meta_key, $meta_value);
        }
        return true;
    }

    /**
     * Update tags for an existing application user.
     * @param string $device_token device token
     * @param array $add_tags array of new tags to add
     * @param array $delete_tags array of new tags to delete
     * @return boolean return true if app user meta is saved.
     */
    public static function updateApplicationUserTagsByDeviceToken($device_token, array $add_tags = null, array $delete_tags = null) {
        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (empty($app_user))
            return false;

        if (isset($add_tags) && !empty($add_tags))
            foreach ($add_tags as $tag) {
                $app_user->addTag($tag);
            }

        if (isset($delete_tags) && !empty($delete_tags))
            foreach ($delete_tags as $tag) {
                $app_user->deleteTag($tag);
            }
            
        return true;
    }

}

?>
