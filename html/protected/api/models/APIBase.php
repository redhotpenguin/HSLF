<?php

abstract class APIBase implements IAPI {

    const AUTH_REQUIRED = 'Authentication required';

    protected $model;
    protected $tableAlias;
    protected $isAuthenticated;
    protected $requiresAuth;

    public function __construct(CActiveRecord $model, $requiresAuth = false) {
        $this->model = $model;
        $this->tableAlias = $model->getTableAlias();
        $this->requiresAuth = $requiresAuth;
    }

    public final function setAuthenticated($authenticated) {
        $this->isAuthenticated = $authenticated;
    }

    public function getList($arguments = array()) {

        // auth is required but user is not authenticated:
        if ($this->requiresAuth && !$this->isAuthenticated)
            return self::AUTH_REQUIRED;;

        // doesn't require auth or is authenticated
        if (!$this->requiresAuth || $this->isAuthenticated) {
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
    }

    public function getSingle($pkID, $arguments = array()) {

        $relations = array();

        if (isset($arguments['relations']) && $arguments['relations'] == 'all') {
            $modelRelations = $this->model->relations();


            foreach ($modelRelations as $relationName => $value) {
                array_push($relations, $relationName);
            }
        }

        // doesn't require auth or is authenticated
        if (!$this->requiresAuth || $this->isAuthenticated) {
            return $this->model->with($relations)->findByPk($pkID);
        } else {
            return self::AUTH_REQUIRED;
        }
    }

}

?>
