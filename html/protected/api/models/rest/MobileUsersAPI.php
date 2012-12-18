<?php

/**
 * Description of MobileUser
 *
 * @author jonas
 */
class MobileUsersAPI implements IAPI {

    public function getList($tenantId, $arguments = array()) {
        return "not supported";
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        return "not supported";
    }

    public function create($tenantId, $arguments = array()) {
        if (!isset($arguments['user']))
            return "Incorrect usage";

        $newUser = CJSON::decode($arguments['user'], false); // decode json string to an stdobject

        if (!is_object($newUser))
            return "Incorrect data";

        $currentDate = new MongoDate();
        $mUser = new MobileUser();

        // save everything
        foreach ($newUser as $key => $value) {
            $mUser->$key = $value;
        }


        // device_identifier + tenant_id == unique 
        $mUser->sessionTenantId = $tenantId;
        $mUser->registration_date = $currentDate;
        $mUser->last_connection_date = $currentDate;


        if ($mUser->save()) {
            return "success";
        }

        if ($mUser->lastErrorCode == 11000) {
            return "Error: User already exists";
        }

        return "failure #{$mUser->lastErrorCode}";
    }

    public function update($tenantId, $mobileUserId, $arguments = array()) {
        if (!isset($arguments['user']))
            return "Incorrect usage";

        $mUser = new MobileUser();
        $tenantId = (int) $tenantId;

        $user = CJSON::decode($arguments['user'], false); // decode json string to an stdobject

        if (!is_object($user))
            return "Incorrect data";

        $conditions = array(
            "tenant_id" => $tenantId,
            "device_identifier" => $mobileUserId
        );

        foreach ($user as $key => $value) {
            $mUser->$key = $value;
        }

        $mUser->sessionTenantId = $tenantId;
        $mUser->device_identifier = $mobileUserId;
        $mUser->last_connection_date = new MongoDate();


        if ($mUser->update($conditions, '$set')) {
            return "success";
        } else {
            return "failure #{$mUser->lastErrorCode}";
        }
    }

    public function requiresAuthentification() {
        return true;
    }

}

?>
