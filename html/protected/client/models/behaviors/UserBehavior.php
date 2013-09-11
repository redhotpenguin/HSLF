<?php

class UserBehavior extends CActiveRecordBehavior {

    /**
     * Return whether  a user is an admin or not
     * administrator is a virtual property
     * @return boolean true or false
     */
    public function isAdministrator() {
        $tenantUserId = $this->getTenantUserId(0, $this->owner->id);

        return Yii::app()->authManager->checkAccess('admin', $tenantUserId);
    }

    public function getTenantUserId($tenantId, $userId = null) {
        if($userId == null){
            $userId = $this->owner->id;
        }
        return $tenantId . "/" . $userId;
    }

    public function updateTasks($tenantId, array $tasks = array()) {

        $tenantUserId = $this->getTenantUserId($tenantId, $this->owner->id);

        Yii::app()->authManager->revokeAll($tenantUserId);


        foreach ($tasks as $task):
            try {
                Yii::app()->authManager->assign($task, $tenantUserId);
            } catch (Exception $e) {
                return false;
            }

        endforeach;

        return true;
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
     * @param integer $userId - optionnal. 
     * @return boolean
     */
    public function belongsToTenant($tenantId, $userId = null) {

        if (!$userId) {
            $userId = $this->owner->id;
        }


        $user = User::model()->with('tenants')->find(
                array(
                    'condition' => "user_id = :user_id AND tenant_id =:tenant_id",
                    'params' => array(
                        ':user_id' => $userId,
                        ':tenant_id' => $tenantId
                    )
                ));

        return ($user != null);
    }

}