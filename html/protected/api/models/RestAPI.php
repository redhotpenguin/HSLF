<?php

/**
 * Model for the API controller
 *
 * @author jonas
 */
class RestAPI extends CModel {

    public function attributeNames() {
        return array();
    }

    /**
     * Register an application user.  Add a new District if it is not already present. Tag UAP user
     * @param string $device_token device token
     * @param string $uap_user_id urban airship user id
     * @param string $device_type device type
     * @param string $state_abbr state abbreviation
     * @param string $district_type district type
     * @param string $district district
     * @param array $optionnal associative array of optional data: user agent, meta, geolocation
     * @return boolean return true if user is saved
     */
    public function registerApplicationUser($device_token, $uap_user_id, $device_type, $state_abbr, $district_type, $district, array $optional = null) {
        $save_result = false;

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


        $app_user->type = $device_type;


        if (isset($optional)) {
            if (key_exists('user_agent', $optional))
                $app_user->user_agent = $optional['user_agent'];

            if (key_exists('geolocation', $optional)) {
                $app_user->latitude = $optional['geolocation']['lat'];
                $app_user->longitude = $optional['geolocation']['long'];
            }
        }


        $app_user->district_id = $this->getDistrictIDCreateIfNotExist($state_abbr, $district_type, $district);

        // save the application user before we give it meta data
        $save_result = $app_user->save();

        if ($save_result) {
            $app_user->synchronizeUAPTags();

            if (isset($optional) && key_exists('meta', $optional))
                $app_user->updateMassMeta($optional['meta']);

            return 'insert_ok';
        }

        else {
            return $app_user->getErrors();
        }
    }

    /**
     * Update an application user tags.
     * @param string $device_token device token
     * @param array $tags tags to be added or deleted
     * @param integer $district_id district id (optional)
     * $param l
     */
    public function updateApplicationUserTags($device_token, array $tags, $district_id = null) {
        $app_user = Application_user::model()->findByAttributes(array('device_token' => $device_token));

        if (!$app_user)
            return 'no_user_found';

        if (isset($district_id)) {
            $app_user->district_id = $district_id;
            $app_user->save();
        }

        if (isset($tags['add_tags'])) {
            foreach ($tags['add_tags'] as $tag) {
                $app_user->addTag($tag);
            }
        }

        if (isset($tags['delete_tags'])) {
            foreach ($tags['delete_tags'] as $tag) {
                $app_user->deleteTag($tag);
            }
        }


        // synchronize tags on URban Airship (only synchronize the valid tags)
        $app_user->synchronizeUAPTags();


        return 'update_tags_ok';
    }

    /**
     * Look for a district id. create it if it doesnt't exist
     * @param string  $state_abbr  abbreviaton of the state
     * @param array  $district_type  district type
     * @param string $district  district name
     * @return return a district id
     */
    public function getDistrictIDCreateIfNotExist($state_abbr, $district_type, $district) {
        $district_attributes = array(
            'state_abbr' => $state_abbr,
            'type' => $district_type,
            'number' => (string) $district,
        );

        $district_id = District::model()->findByAttributes($district_attributes)->id;

        // a district didnt match the criteria, add it
        if (!$district_id) {
            try {
                $new_district = new District();
                $new_district->state_abbr = $state_abbr;
                $new_district->type = $district_type;
                $new_district->number = $district;

                $new_district->save();

                $district_id = $new_district->id;
            } catch (Exception $exception) {
                error_log('could not create new district: ' . $exception->getMessage());
                return false;
            }
        }

        return $district_id;
    }

    public function updateUAPTags($uap_user_id, $device_token, $tags) {
        $uap_notifier = new UrbanAirshipNotifier();
        return $uap_notifier->updateRichUserTags($uap_user_id, $device_token, $tags);
    }

}