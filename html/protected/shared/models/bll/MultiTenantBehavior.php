<?php

/**
 * behavior to enable multi tenancy
 * @todo: refactor this class and improve unit tests
 */
class MultiTenantBehavior extends CActiveRecordBehavior {

    const ILLEGAL_ACTION = 'Illegal action: action will be reported';

    private function getCurrentTenantId(CModel $owner) {
        if ($owner->sessionTenantId != null) {
            return $this->owner->sessionTenantId;
        } elseif (!Yii::app()->user->isGuest && Yii::app()->user->tenant_id != null) { // only logged in users can have a tenant_id
            return Yii::app()->user->tenant_id;
        } else {
            return null;
        }
    }

    /**
     * Handle Multi tenancy for CActiveRecord models (find and save)
     * @param CActiveRecord model
     * @param string $action - find or save
     */
    private function handleActiveRecord(CActiveRecord $owner, $action, $event = null) {
        $user_tenant_id = $this->getCurrentTenantId($owner);
        if ($user_tenant_id == null) {
            return;
        } 

        if ($action == 'find') {
            $c = $owner->getDbCriteria();
            $condition = $c->condition;
            $relations = $c->with;

            if (strlen($condition) > 0) {
                $condition = "$condition AND ";
            }

            $alias = $owner->getTableAlias(false, false);

            if ($action == 'find') {
                if ($owner->hasAttribute('tenant_id')) {
                    $condition.= $alias . '.tenant_id = ' . $user_tenant_id;
                    $c->condition = $condition;
                } elseif ($owner->parentName) {
                    $relations = array($owner->parentRelationship);
                    $c->with = $relations;
                    $c->addCondition("tenant_id =  {$user_tenant_id}", 'AND');
                }
            }
        } elseif ($action == 'save') {

            if ($owner->hasAttribute('tenant_id')) {
                //tie this model to the actual tenant by setting the tenantid attribute
                $owner->tenant_id = $user_tenant_id;
            }

            $relations = $owner->relations();

            foreach ($relations as $relation => $value) {

                if (isset($owner->$relation->id)) {

                    // relation does not have a tenant  id column. Ex: state, district, true join table
                    if (!isset($owner->$relation->tenant_id)) {
                        continue;
                    }

                    $relationTenantId = $owner->$relation->tenant_id;

                    if ($user_tenant_id != $relationTenantId) {
                        $ownerClassName = get_class($this->owner);
                        error_log("Model {$ownerClassName} and relation {$relation} tenant id ($user_tenant_id != $relationTenantId)does not match");
                        throw new Exception(self::ILLEGAL_ACTION);
                    }
                }
            }



            return parent::beforeSave($event);
        }
    }

    /**
     * Handle Multi tenancy for ActiveMongoDocument models (find and save)
     * @param ActiveMongoDocument model
     * @param string $action - find or save
     */
    private function handleActiveMongoDocument(ActiveMongoDocument $owner, $action) {

        $user_tenant_id = $this->getCurrentTenantId($this->owner);
        if ($user_tenant_id == null) {
            return;
        }

        if ($action == 'find') {
            $owner->searchAttributes['tenant_id'] = $user_tenant_id;
        } elseif ($action == 'save') {

            // MongoDB does not allow other fields when '$set' or '$push' are set
            if (!isset($this->owner->fields['$set']) && !isset($this->owner->fields['$push'])) {
                $this->owner->fields['tenant_id'] = (int) $user_tenant_id;
            }
        }
    }

    /**
     * beforeFind - event handler
     * Make sure that only models with a matching tenant id can be retrieved
     * @param CEvent event
     */
    public function beforeFind($event) {
        if ($this->owner instanceof ActiveMongoDocument) {
            $this->handleActiveMongoDocument($this->owner, 'find');
        } else {
            $this->handleActiveRecord($this->owner, 'find');
        }
    }

    /**
     * beforeFind - event handler
     * Make sure that only models with a matching tenant id can be saved
     * @param CEvent event
     */
    public function beforeSave($event) {
        if ($this->owner instanceof ActiveMongoDocument) {
            $this->handleActiveMongoDocument($this->owner, 'save');
        } else { //active record 
            $this->handleActiveRecord($this->owner, 'save', $event);
        }
    }

}