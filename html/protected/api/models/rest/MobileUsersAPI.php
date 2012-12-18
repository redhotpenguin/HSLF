<?php

/**
 * Description of MobileUser
 *
 * @author jonas
 */
class MobileUsersAPI implements IAPI {

    const ERROR_USER_NOT_FOUND = "user not found";
    const ERROR_USER_ALREADY_EXISTS = "user already exists";
    const ERROR_INCORRECT_USAGE = "incorrect usage";
    const ERROR_INCORRECT_DATA = "incorrect data";
    const SUCCESS = "success";

    public function getList($tenantId, $arguments = array()) {
        return "not supported";
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        return "not supported";
    }

    public function create($tenantId, $arguments = array()) {
        if (!isset($arguments['user']))
            return self::ERROR_INCORRECT_USAGE;

        $userData = CJSON::decode($arguments['user'], false); // decode json string to an stdobject

        if (!is_object($userData))
            return self::ERROR_INCORRECT_DATA;

        $currentDate = new MongoDate();
        $mUser = new MobileUser();

        // save everything
        foreach ($userData as $key => $value) {
            $mUser->$key = $value;
        }


        // device_identifier + tenant_id == unique 
        $mUser->sessionTenantId = $tenantId;
        $mUser->registration_date = $currentDate;
        $mUser->last_connection_date = $currentDate;


        if ($mUser->save()) {
            return self::SUCCESS;
        }

        if ($mUser->lastErrorCode == 11000) {
            return self::ERROR_USER_ALREADY_EXISTS;
        }

        return "failure #{$mUser->lastErrorCode}";
    }

    public function update($tenantId, $mobileUserId, $arguments = array()) {
        if (!isset($arguments['user']))
            return self::ERROR_INCORRECT_USAGE;
        
        $mUser = new MobileUser();
        $tenantId = (int) $tenantId;

        $userData = CJSON::decode($arguments['user'], true); // decode json string as an array

        if (!is_array($userData))
            return self::ERROR_INCORRECT_DATA;

        $conditions = array(
            "tenant_id" => $tenantId,
            "device_identifier" => $mobileUserId
        );

        $set = $userData['set'];
        $push = $userData['push'];

        $set['last_connection_date'] = new MongoDate();

        $updateResult = $mUser->update($conditions, $set, $push);

        if ($updateResult === true) {
            return self::SUCCESS;
        } elseif ($updateResult === -1) {
            return self::ERROR_USER_NOT_FOUND;
        } else {
            return "failure #{$mUser->lastErrorCode}";
        }
    }

    public function requiresAuthentification() {
        return true;
    }

}

?>
