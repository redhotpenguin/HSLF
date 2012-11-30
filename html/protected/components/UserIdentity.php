<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Authenticates a user.

     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {


        $user = User::model()->findByAttributes(array('username' => $this->username));
        
       
        if ($user === null) { // No user found!
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            error_log('invalid username: ' . $this->username);
        } else if ($user->password !== get_hash($this->password)) { // Invalid password!
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else { // Okay!
            $this->errorCode = self::ERROR_NONE;
            $this->setState('role', $user->role);
            $this->setState('tenant_account_id', $user->tenant_account_id);
        }

        return !$this->errorCode;
    }
    
    public function getId(){
        return "foobar";
    }

}