<?php

class UserBehavior extends CActiveRecordBehavior {

    /**
     * add or update the user role in table authassignement
     * @param Cevent $event
     */
    public function afterSave($event) {
        parent::afterSave($event);


        foreach ($this->owner->tenants as $tenant) {
            $tenantUserId = $tenant->id . ',' . $this->owner->id;

            Yii::app()->authManager->revokeAll($tenantUserId);
        }

        if (!empty($this->owner->rolesByTenant)) {

            logIt($this->owner->rolesByTenant);


            foreach ($this->owner->rolesByTenant as $tenantId => $role) {
                if ($role == null || empty($role))
                    continue;

                $tenantUserId = $tenantId . ',' . $this->owner->id;
                try {
                    Yii::app()->authManager->assign($role, $tenantUserId);
                } catch (Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }

    /**
     * Remove user from the assignment table (authassignement)
     * @param Cevent $event
     */
    public function beforeDelete($event) {

        $assignments = Yii::app()->authManager->getAuthAssignments($this->owner->id);

        foreach ($assignments as $assignment) {
            Yii::app()->authManager->revoke($assignment->itemName, $this->owner->id);
        }

        parent::beforeDelete($event);
    }

    public function afterLoadModel() {
        $this->owner->role = $this->getRole();
    }

    /**
     * deprecated
     */
    public function getRole() {
        if (( $item = Yii::app()->authManager->getAuthAssignment('admin', $this->owner->id))) {
            $role = $item->itemName;
        } elseif (( $item = Yii::app()->authManager->getAuthAssignment('publisher', $this->owner->id))) {
            $role = $item->itemName;
        } else {
            $role = null;
        }

        return $role;
    }

    /**
     * get a user role for a specific tenant
     * @param  integer $tenantId - tenant id
     * @return CAuthAssignment object
     */
    public function getRoleByTenant($tenantId) {
        $tenantUserId = $tenantId . ',' . $this->owner->id;

        if (( $item = Yii::app()->authManager->getAuthAssignment('admin', $tenantUserId))) {
            $role = $item->itemName;
        } elseif (( $item = Yii::app()->authManager->getAuthAssignment('publisher', $tenantUserId))) {
            $role = $item->itemName;
        } else {
            $role = null;
        }

        return $role;
    }

    /**
     * Add a user to a tenant
     * @param integer $tenant tenant id
     * @return boolean
     */
    public function addToTenant($tenantId) {

        $sql = "INSERT INTO tenant_user (tenant_id, user_id) VALUES(:tenant_id, :user_id)";

        $connection = Yii::app()->db;

        $command = $connection->createCommand($sql);

        $userId = $this->owner->id;

        $command->bindParam(":tenant_id", $tenantId, PDO::PARAM_INT);
        $command->bindParam(":user_id", $userId, PDO::PARAM_INT);
        try {
            $command->execute();
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Add a user to a tenant
     * @param integer $tenant tenant id
     * @return boolean
     */
    public function removeFromTenant($tenantId) {

        $sql = "DELETE FROM tenant_user WHERE (tenant_id = :tenant_id AND user_id =  :user_id)";

        $connection = Yii::app()->db;

        $command = $connection->createCommand($sql);

        $userId = $this->owner->id;

        $command->bindParam(":tenant_id", $tenantId, PDO::PARAM_INT);
        $command->bindParam(":user_id", $userId, PDO::PARAM_INT);
        try {
            $command->execute();
            $result = true;
        } catch (Exception $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * Verify that a user belong to a  tenant
     * @param integer $tenantId tenant id
     * @return boolean
     */
    public function belongsToTenant($tenantId) {
        $user = User::model()->with('tenants')->find(
                array(
                    'condition' => "user_id = :user_id AND tenant_id =:tenant_id",
                    'params' => array(
                        ':user_id' => $this->owner->id,
                        ':tenant_id' => $tenantId
                    )
                ));

        return ($user != null);
    }

}