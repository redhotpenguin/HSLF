<?php

class MultiTenantBehavior extends CActiveRecordBehavior {

    const ILLEGAL_ACTION = 'Illegal action: action will be reported';

    public function beforeFind($event) {
        if ($this->owner instanceof ActiveMongoDocument) {

            // sessionTenantId: required when doing unit tests and by API 
            // Yii::app()->user->tenant_id: tenant id of the logged in user

            if (!Yii::app()->user->isGuest && Yii::app()->user->tenant_id != null) { // only logged in users can have a tenant_id
                $user_tenant_id = Yii::app()->user->tenant_id;
                $this->owner->sessionTenantId = $user_tenant_id;
            }

            // before save:
            // $this->owner->fields['tenant_id'] = $user_tenant_id;
        } else { // activerecord
            if ($this->owner->hasAttribute('tenant_id')) {

                //restrict queries to the actual tenant by manipulating the model's DbCriteria
                $c = $this->owner->getDbCriteria();
                $condition = $c->condition;
                $relations = $c->with;

                if (strlen($condition) > 0) {
                    $condition = "$condition AND ";
                }

                $alias = $this->owner->getTableAlias(false, false);


                if ($this->owner->sessionTenantId != null) {
                    $user_tenant_id = $this->owner->sessionTenantId;
                } elseif (!Yii::app()->user->isGuest && Yii::app()->user->tenant_id != null) { // only logged in users can have a tenant_id
                    $user_tenant_id = Yii::app()->user->tenant_id;
                } else {
                    return;
                }

                if ($this->owner->hasAttribute('tenant_id')) {
                    $condition.= $alias . '.tenant_id = ' . $user_tenant_id;
                    $c->condition = $condition;
                } elseif ($this->owner->parentName) {
                    $relations = array($this->owner->parentRelationship);
                    $c->with = $relations;

                    $c->addCondition("tenant_id =  {$user_tenant_id}", 'AND');
                }
            }
        }
    }

    public function beforeSave($event) {


        if ($this->owner instanceof ActiveMongoDocument) {

            if ($this->owner->sessionTenantId != null) {
                $user_tenant_id = $this->owner->sessionTenantId;
            } elseif (!Yii::app()->user->isGuest && Yii::app()->user->tenant_id != null) { // only logged in users can have a tenant_id
                $user_tenant_id = Yii::app()->user->tenant_id;
                $this->owner->sessionTenantId = $user_tenant_id;
            } else {
                return;
            }

            $this->owner->fields['tenant_id'] = $user_tenant_id;
        } else {
            if ($this->owner->hasAttribute('tenant_id')) {

                if ($this->owner->sessionTenantId != null) {
                    $user_tenant_id = $this->owner->sessionTenantId;
                } elseif (!Yii::app()->user->isGuest && Yii::app()->user->tenant_id != null) { // only logged in users can have a tenant_id
                    $user_tenant_id = Yii::app()->user->tenant_id;
                } else {
                    return;
                }

                //tie this model to the actual tenant by setting the tenantid attribute
                $this->owner->tenant_id = $user_tenant_id;

                $relations = $this->owner->relations();
                foreach ($relations as $relation => $value) {
                    if (isset($this->owner->$relation->id)) {
                        // check that $relationId actually belongs to the current tenant id
                        $modelTenantId = $this->owner->tenant_id;

                        // relation does not have a tenant  id column. Ex: state, district, true join table
                        if (!isset($this->owner->$relation->tenant_id)) {
                            continue;
                        }

                        $relationTenantId = $this->owner->$relation->tenant_id;

                        if ($modelTenantId != $relationTenantId) {
                            $ownerClassName = get_class($this->owner);
                            error_log("Model {$ownerClassName} and relation {$relation} tenant id ($modelTenantId != $relationTenantId)does not match");
                            throw new Exception(self::ILLEGAL_ACTION);
                        }
                    } else { // many-many relationship ($this->owner->$relation is an array)
                        //   error_log("debug: ". $relation);
                    }
                }
            }


            return parent::beforeSave($event);
        }
    }

}

?>
