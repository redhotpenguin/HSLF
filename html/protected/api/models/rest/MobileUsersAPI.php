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

        $userData = CJSON::decode($arguments['user'], false); // decode json string to an stdobject

        if (!is_object($userData))
            return "Incorrect data";

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

        $userData = CJSON::decode($arguments['user'], true); // decode json string as an array
        //  logIt($userData);

        if (!is_array($userData))
            return "Incorrect data";

        $conditions = array(
            "tenant_id" => $tenantId,
            "device_identifier" => $mobileUserId
        );

        $set = $userData['set'];
        $push = $userData['push'];

        $set['last_connection_date'] = new MongoDate();

        $updateResult = $mUser->update($conditions, $set, $push);
        
        if ( $updateResult === true ) {
            return "success";
        } elseif($updateResult === -1) {
            return "record does not exist";
        }else{
            return "failure #{$mUser->lastErrorCode}";
        }
    }

    public function requiresAuthentification() {
        return true;
    }

}

?>
