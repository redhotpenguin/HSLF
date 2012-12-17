<?php

/**
 * Description of MobileUser
 *
 * @author jonas
 */
class MobileUsersAPI implements IAPI {

    //put your code here
    public function getList($tenantId, $arguments = array()) {
        return "denied";
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        return "denied";
    }

    public function create($tenantId, $arguments = array()) {
        if (!isset($arguments['user']))
            return "Incorrect usage";


        $newUser = CJSON_Nested::decode($arguments['user'], false); // decode json string to an stdobject

        logIt($newUser);
        
        $mUser = new MobileUser();
        $mUser->sessionTenantId = $tenantId;
        $mUser->name = $newUser->name;
        
        $mUser->save();

        return "success";
    }

    public function setAuthenticated($authenticated) {
        //  echo "auhtentidcated:? $authenticated";
    }

}

?>
