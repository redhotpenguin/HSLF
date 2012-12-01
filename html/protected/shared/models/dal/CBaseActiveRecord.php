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

    
    /* override CActiveRecord.count() to trigger beforeFind */
    public function count($condition = '', $params = array()) {
        $this->beforeFind();
        Yii::trace(get_class($this) . '.count()', 'system.db.ar.CActiveRecord');
        $builder = $this->getCommandBuilder();
        $criteria = $builder->createCriteria($condition, $params);
        $this->applyScopes($criteria);

        if (empty($criteria->with))
            return $builder->createCountCommand($this->getTableSchema(), $criteria)->queryScalar();
        else {
            $finder = new CActiveFinder($this, $criteria->with);
            return $finder->count($criteria);
        }
    }

}

?>
