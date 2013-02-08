<?php

class WebUser extends CWebUser {

    private $_access = array();
    private $_userModel; // User active record

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
        parent::login($identity, $duration);
        $this->_userModel = $identity->getUser();
        $this->setState('__userInfo', $this->_userModel);
    }

    /**
     * set the user current tenant
     * Does not use $_SESSION
     * @param string $tenant name
     * @todo: error check
     */
    public function setCurrentUserTenant($tenantName) {

        $tenant = Tenant::model()->findByAttributes(array('name' => $tenantName));
        if ($tenant == null)
            return false;

        $user = User::model()->getUser(Yii::app()->user->id);


        if ($user == null)
            return false;

        if ($user->belongsToTenant($tenant->id)) {
            //     error_log("setting tenant");
            Yii::app()->params['current_tenant'] = $tenant;
            return true;
        }
        else
            return false;
    }

    public function getCurrentTenant() {
        //   print_r(Yii::app()->params);
        return Yii::app()->params['current_tenant'];
    }

    public function getId() {
        return $this->getState('userId');
    }
    
    /**
     * return the composite primary key from tenant_user
     * @return
     */
    public function getUserTenantId(){
        return $this->getCurrentTenant()->id.','.$this->getState('userId');
    }

    public function getModel() {
        if (empty($this->_userModel))
            $this->_userModel = User::model()->getUser($this->getId());

        return $this->_userModel;
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

        $access = Yii::app()->getAuthManager()->checkAccess($operation, $this->getUserTenantId(), $params);
        if ($allowCaching) {
            $this->_access[$operation] = $access;
        }

        return $access;
    }

}
