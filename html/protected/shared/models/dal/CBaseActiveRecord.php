<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CMultiTenantActiveRecord
 *
 * @author jonas
 */
abstract class CBaseActiveRecord extends CActiveRecord {

    public $sessionTenantAccountId;

    public final function defaultScope() {

        if (Yii::app()->user->id != null) {
            $user_tenant_account_id = Yii::app()->user->tenant_account_id;
        } elseif ($this->sessionTenantAccountId != null) {
            $user_tenant_account_id = $this->sessionTenantAccountId;
        } else {
            return array();
        }

        $condition = $this->getTableAlias(false, false) . '.tenant_account_id=' . $user_tenant_account_id;

        return array(
            'condition' => $condition,
        );
    }
   

}

?>
