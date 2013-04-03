<?php

class WebUser extends CWebUser {

    private $_access = array();
    private $_userModel; // User active record
    private $permissions = array(); // logged in users permissions

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
     */
    public function setLoggedInUserTenant($tenantName) {

        $tenant = Tenant::model()->findByAttributes(array('name' => $tenantName));
        if ($tenant == null)
            return false;

        $user = $this->getModel(Yii::app()->user->id);

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

    public function getLoggedInUserTenant() {
        return Yii::app()->params['current_tenant'];
    }

    public function getId() {
        return $this->getState('userId');
    }

    /**
     * Get the key identifiying a user for a specific tenant. Ex 2/13.  2 = tenant id and 13 = user id
     * @return mixed tenant user id or false
     */
    public function getLoggedInTenantUserId() {

        $model = $this->getModel();

        if (!$model)
            return false;


        if (($tenant = $this->getLoggedInUserTenant()) != null)
            return $model->getTenantUserId($tenant->id, $this->getState('userId'));

        return $model->getTenantUserId(0, $this->getState('userId')); // 0 means no tenant. Ex: (super)admin dashboard or home page
    }

    public function getModel($relations = array()) {
        if (empty($this->_userModel))
            $this->_userModel = User::model()->getUser($this->getId(), $relations);

        return $this->_userModel;
    }

    /**
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

        $access = Yii::app()->getAuthManager()->checkAccess($operation, $this->getLoggedInTenantUserId(), $params);
        if ($allowCaching) {
            $this->_access[$operation] = $access;
        }

        return $access;
    }

    /**
     * Check the permission for the logged in user
     * cache all the user permissions the first time this function is executed
     * @param string permission name
     */
    public function hasPermission($permissionName) {

        if (in_array($permissionName, $this->permissions)) {
            return true;
        }

        $cAuthAssignments = Yii::app()->getAuthManager()->getAuthAssignments($this->getLoggedInTenantUserId());

        if (is_array($cAuthAssignments))
            foreach ($cAuthAssignments as $cAuthAssignment) {
                array_push($this->permissions, $cAuthAssignment->itemName);
            }

        return (in_array($permissionName, $this->permissions));
    }

}
