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

    public $sessionTenantId;
    public $parentTenantName = null;
    public $parentRelationship;

    

    
    public function hasParentTenant() {
        return ($this->parentTenantName != null );
    }

    public function getParentTenant() {
               
        $parentId = $this->{$this->parentRelationship}->id;
                 
        $parentModel = new $this->parentTenantName();
        
        return $parentModel->findByPk($parentId);
    }

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
