<?php

class MultiTenantBehavior extends CActiveRecordBehavior {

    public function beforeFind($event) {
        //restrict queries to the actual tenant by manipulating the model's DbCriteria
        $c = $this->owner->getDbCriteria();
        $condition = $c->condition;
        if (strlen($condition) > 0) {
            $condition = "('.$condition.') AND ";
        }

        $alias = $this->owner->getTableAlias(false, false);

        $condition.= $alias.'.tenant_account_id = ' . Yii::app()->user->tenant_account_id;

        $c->condition = $condition;
    }

    public function beforeSave($event) {
        //tie this model to the actual tenant by setting the tenantid attribute
        $this->owner->tenant_account_id = Yii::app()->user->tenant_account_id;

        return parent::beforeSave($event);
    }

}

?>
