<?php

class UserBehavior extends CActiveRecordBehavior {

    /**
     * add or update the user role in table authassignement
     * @param Cevent $event
     */
    public function afterSave($event) {
        parent::afterSave($event);

        $assignments = Yii::app()->authManager->getAuthAssignments($this->owner->id);

        // remove all assignements, if any
        if ($assignments != null) {
            foreach ($assignments as $assignment) {
                Yii::app()->authManager->revoke($assignment->itemName, $this->owner->id);
            }
        }

        if (!empty($this->owner->role)) {
            Yii::app()->authManager->assign($this->owner->role, $this->owner->id);
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