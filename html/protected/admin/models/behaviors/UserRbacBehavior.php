<?php

class UserRbacBehavior extends CActiveRecordBehavior {

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
            error_log("test");
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

}