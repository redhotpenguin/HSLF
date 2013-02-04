<?php

/**
 * behavior to enable multi tenancy
 * @todo: refactor this class and improve unit tests
 */
class MultiTenantBehavior extends CActiveRecordBehavior {

    const ILLEGAL_ACTION = 'Illegal action: action will be reported';

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

    /**
     * retrieve the current tenant id set in Yii's registry 
     * Yii::app()->params['current_tenant'] or Yii::app()->params['current_tenant_id']
     * @param CModel model
     * @return integer tenant id or null
     */
    private function getCurrentTenantId(CModel $owner) { 
        if (Yii::app()->params['current_tenant_id'] != null) {
            return Yii::app()->params['current_tenant_id'];
        } elseif (!Yii::app()->user->isGuest && Yii::app()->user->getCurrentTenant() != null) { // only logged in users can have a tenant_id
            return Yii::app()->user->getCurrentTenant()->id;
        } else {
            return null;
        }
    }

    /**
     * Handle Multi tenancy for CActiveRecord models (find and save)
     * @param CActiveRecord $owner model
     * @param string $action - find or save
     */
    private function handleActiveRecord(CActiveRecord $owner, $action, $event = null) {
        $userTenantId = $this->getCurrentTenantId($owner);
        if ($userTenantId == null) {
            return;
        }

        if ($action == 'find') {
            return $this->handleActiveRecordBeforeFind($owner, $userTenantId, $event);
        } elseif ($action == 'save') {
            return $this->handleActiveRecordBeforeSave($owner, $userTenantId, $event);
        }
    }

    /**
     * handle beforeFind event for ActiveRecords
     * @param CActiveRecord $owner model
     * @param integer user tenant id
     * @param CEvent event
     */
    private function handleActiveRecordBeforeFind(CActiveRecord $owner, $userTenantId, $event) {

        $c = $owner->getDbCriteria();
        $condition = $c->condition;
        $relations = $c->with;

        if (strlen($condition) > 0) {
            $condition = "$condition AND ";
        }

        $alias = $owner->getTableAlias(false, false);

        if ($owner->hasAttribute('tenant_id')) {
            $condition.= $alias . '.tenant_id = ' . $userTenantId;
            $c->condition = $condition;
        } elseif ($owner->parentName) { // indirect relationship between model and tenant table
            if ($c->with == null) {
                $c->with = array();
            }

            // make sure relation array doesnt have dupes
            if (in_array($owner->parentRelationship, $c->with)) {
                $relations = $c->with;
            } else { // relation (withs) array has been set before this behavior has been executed, make sure we don't override values
                $relations = array_merge($c->with, array($owner->parentRelationship));
            }

            $c->with = $relations;
            $c->addCondition("tenant_id =  {$userTenantId}", 'AND');
        }


        return parent::beforeFind($event);
    }

    /**
     * handle beforeSave event for ActiveRecords
     * @param CActiveRecord $owner model
     * @param integer user tenant id
     * @param CEvent event
     */
    private function handleActiveRecordBeforeSave(CActiveRecord $owner, $userTenantId, $event) {
        if ($owner->hasAttribute('tenant_id')) {
            //tie this model to the actual tenant by setting the tenantid attribute
            $owner->tenant_id = $userTenantId;
        }

        $relations = $owner->relations();

        foreach ($relations as $relation => $value) {
                        
            if (isset($owner->$relation->id)) {

                // relation does not have a tenant  id column. Ex: state, district, true join table
                if (!isset($owner->$relation->tenant_id)) {
                    continue;
                }

                $relationTenantId = $owner->$relation->tenant_id;

                if ($userTenantId != $relationTenantId) {
                    $ownerClassName = get_class($this->owner);
                    error_log("Model {$ownerClassName} and relation {$relation} tenant id ($userTenantId != $relationTenantId)does not match");
                    throw new Exception(self::ILLEGAL_ACTION);
                }
            }
        }


        return parent::beforeSave($event);
    }

    /**
     * Handle Multi tenancy for ActiveMongoDocument models (find and save)
     * @param ActiveMongoDocument model
     * @param string $action - find or save
     */
    private function handleActiveMongoDocument(ActiveMongoDocument $owner, $action) {

        $userTenantId = $this->getCurrentTenantId($this->owner);
        if ($userTenantId == null) {
            return;
        }

        if ($action == 'find') {
            $owner->searchAttributes['tenant_id'] = $userTenantId;
        } elseif ($action == 'save') {

            // MongoDB does not allow other fields when '$set' or '$push' are set
            if (!isset($this->owner->fields['$set']) && !isset($this->owner->fields['$push'])) {
                $this->owner->fields['tenant_id'] = (int) $userTenantId;
            }
        }
    }

}