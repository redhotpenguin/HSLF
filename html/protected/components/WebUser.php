<?php

class WebUser extends CWebUser {

    private $_access = array();

    public function __get($name) {

        if ($this->hasState('__userInfo')) {

            $user = $this->getState('__userInfo', array());

            if (isset($user[$name])) {
                return $user[$name];
            }
        }

        return parent::__get($name);
    }

    public function login($identity, $duration = 0) {
        $this->setState('__userInfo', $identity->getUser());
        parent::login($identity, $duration);
    }

    public function setSessionTenantByTenantName($tenantName) {
        
        $tenant = Tenant::model()->findByAttributes( array('name' => $tenantName)  );
        
        error_log($tenant->id);
        


    
          $this->setState('tenant_id', 1);
    
    }

    /**
     * Override 
     * Performs access check for this user.
     * @param string $operation the name of the operation that need access check.
     * @param array $params name-value pairs that would be passed to business rules associated
     * {@link getId()} when {@link CDbAuthManager} or {@link CPhpAuthManager} is used.
     * @param boolean $allowCaching whether to allow caching the result of access check.
     * @return boolean whether the operations can be performed by this user.
     */
    public function checkAccess($operation, $params = array(), $allowCaching = true) {
        if ($allowCaching && isset($this->_access[$operation])) {
            return $this->_access[$operation];
        }

        $access = Yii::app()->getAuthManager()->checkAccess($operation, Yii::app()->user->id, $params);
        if ($allowCaching) {            
            $this->_access[$operation] = $access;
        }

        return $access;
    }

}
