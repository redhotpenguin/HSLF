<?php

abstract class APIBase implements IAPI {

    protected $model;
    protected $tableAlias;

    public function __construct(CActiveRecord $model) {
        $this->model = $model;
        $this->tableAlias = $model->getTableAlias();
    }

    public function getList($tenantId, $arguments = array()) {
        $relations = array();
        $attributes = array();
        $options = array('order' => $this->tableAlias . '.id desc');

        // check if relationships are set
        if (isset($arguments['relations'])) {
            $relations = explode(',', $arguments['relations']);
        }

        // handle orderering
        if (isset($arguments['orderBy']) && isset($arguments['order'])) {
            $arguments['order'] = strtoupper($arguments['order']);
            if ($this->model->hasAttribute($arguments['orderBy']) && ( $arguments['order'] == 'ASC' || $arguments['order'] == 'DESC')) {
                $options['order'] = $arguments['orderBy'] . " " . $arguments['order'];

                $options['order'] = "{$this->tableAlias}.{$arguments['orderBy']} {$arguments['order']}";
            }
        }

        // limit results
        if (isset($arguments['limit']) && is_numeric($arguments['limit'])) {
            $options['limit'] = $arguments['limit'];
        }


        // filter by attribute
        if (isset($arguments['attributeValue']) && isset($arguments['attribute']) && $this->model->hasAttribute($arguments['attribute'])) {
            $attributes = array($arguments['attribute'] => $arguments['attributeValue']);
        }


        try {
            $result = $this->model->with($relations)->findAllByAttributes($attributes, $options);
        } catch (CDbException $cdbE) {
            //echo $cdbE->getMessage();
            $result = "no_results";
        }
        return $result;
    }

    public function getSingle($tenantId, $pkID, $arguments = array()) {
        $relations = array();



        if (isset($arguments['relations'])) {
            if ($arguments['relations'] == 'all') {
                $modelRelations = $this->model->relations();
                foreach ($modelRelations as $relationName => $value) {
                    array_push($relations, $relationName);
                }
            } else {
                $relations = explode(',', $arguments['relations']);
            }
        }

        try {
            $result = $this->model->with($relations)->findByPk($pkID);
        } catch (CDbException $cdbE) {
            //echo $cdbE->getMessage();
            $result = "no_results";
        }

        return $result;
    }

    public function create($tenantId, $arguments = array()) {
        return "operation not supported";
    }

    public function update($tenantId, $id, $arguments = array()) {
        return "operation not supported";
    }

    public function requiresAuthentification() {
        return false;
    }

}