<?php

class MultiTenantBehavior extends CActiveRecordBehavior {

    public function beforeFind($event) {

        //restrict queries to the actual tenant by manipulating the model's DbCriteria
        $c = $this->owner->getDbCriteria();
        $condition = $c->condition;


        if (strlen($condition) > 0) {
            $condition = "$condition AND ";
        }

        $alias = $this->owner->getTableAlias(false, false);

        if (Yii::app()->user->id != null) {
            $user_tenant_account_id = Yii::app()->user->tenant_account_id;
        } elseif ($this->owner->sessionTenantAccountId != null) {
            $user_tenant_account_id = $this->owner->sessionTenantAccountId;
        } else {
            return;
        }

        $condition.= $alias . '.tenant_account_id = ' . $user_tenant_account_id;
        $c->condition = $condition;
    }

    public function beforeSave($event) {
        //tie this model to the actual tenant by setting the tenantid attribute
        $this->owner->tenant_account_id = Yii::app()->user->tenant_account_id;

        $relations = $this->owner->relations();
        foreach ($relations as $relation => $value) {
            if (isset($this->owner->$relation->id)) {
                // check that $relationId actually belongs to the current tenant id
                $modelTenantAccountId = $this->owner->tenant_account_id;

                // relation does not have a tenant account id column. Ex: state, district, true join table
                if (!isset($this->owner->$relation->tenant_account_id)) {
                    continue;
                }

                $relationTenantAccountId = $this->owner->$relation->tenant_account_id;

                if ($modelTenantAccountId != $relationTenantAccountId) {
                    $ownerClassName = get_class($this->owner);
                    error_log("Model {$ownerClassName} and relation {$relation} tenant id ($modelTenantAccountId != $relationTenantAccountId)does not match");
                    throw new Exception("Illegal action: action will be reported");
                }
            } else { // many-many relationship ($this->owner->$relation is an array)
                error_log("debug: ". $relation);
            }
        }



        return parent::beforeSave($event);
    }

}

?>
