<?php

class MultiTenantBehavior extends CActiveRecordBehavior {
    /*
      public function beforeFind($event) {


      error_log("before find");
      //restrict queries to the actual tenant by manipulating the model's DbCriteria
      $c = $this->owner->getDbCriteria();
      $condition = $c->condition;
      error_log("condition1: " . $condition);



      if (strlen($condition) > 0) {
      $condition = "('.$condition.') AND ";
      }

      $alias = $this->owner->getTableAlias(false, false);


      if (Yii::app()->user->id != null) {
      $user_tenant_account_id = Yii::app()->user->tenant_account_id;
      } elseif ($this->owner->sessionTenantAccountId != null) {
      $user_tenant_account_id = $this->owner->sessionTenantAccountId;
      } else {
      return;
      }

      // $condition.= $alias . '.tenant_account_id = ' . $user_tenant_account_id;
      //  $c->condition = $condition;

      error_log("condition: " . $condition);
      } */
    

    public function beforeSave($event) {
        error_log("before save");
         

        $relations = $this->owner->relations();

        foreach ($relations as $relation => $values) {
                  print_r($this->owner->$relation);
        }

        //tie this model to the actual tenant by setting the tenantid attribute
        $this->owner->tenant_account_id = Yii::app()->user->tenant_account_id;

       // return parent::beforeSave($event);
    }

}

?>
