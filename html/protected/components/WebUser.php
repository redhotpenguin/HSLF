<?php

class WebUser extends CWebUser {

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

    public function setTenant($tennantAccountId) {
        $userId = Yii::app()->user->id;

        $tenantUser = TenantUser::model()->findByAttributes(
                array("user_id" => $userId,
                    "tenant_id" => $tennantAccountId
                )
        );

        // current user belongs to tenant
        if ($tenantUser) {
            $this->setState('tenant_id', $tennantAccountId);
            $this->setState('role', $tenantUser->role);
        } else {
            error_log("current user #$userId does not belong to tenant #$tennantAccountId");
        }
    }

    /**
     * override checkAccess
     * Use current user ID (integer) instead of username
     */
    public function checkAccess($operation, $params = array(), $allowCaching = true) {


        $id = Yii::app()->user->id;

        $access = Yii::app()->getAuthManager()->checkAccess($operation, $id, $params);


        return $access;
    }

}
