<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    private $user;

    /**
     * Authenticates a user.

     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $this->user = User::model()->findByAttributes(array('username' => $this->username));

        if ($this->user === null) { // No user found!
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($this->user->password !== get_hash($this->password)) { // Invalid password!
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else { // Okay!
            $this->errorCode = self::ERROR_NONE;
            $this->setState('authenticated', true);
            $this->setState('userId', $this->user->id);
        }

        return !$this->errorCode;
    }
    
    public function getUser(){
        return $this->user;
    }

}