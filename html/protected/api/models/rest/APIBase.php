<?php

abstract class APIBase implements IAPI {

    public $cacheDuration;
    protected $model;
    protected $tableAlias;

    public function __construct(CActiveRecord $model) {
        $this->cacheDuration = Yii::app()->params->normal_cache_duration;
        $this->model = $model;
        $this->tableAlias = $model->getTableAlias();
    }

    public function getList($tenantId, $arguments = array()) {

        $cacheKey = APIBase::cacheKeyBuilder(get_class($this->model), $tenantId, $arguments);

        // serve from cache?
        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }
        
        // cache hasn't been found, build it
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
            return "no_results";
        }

        if (!empty($result)) {
            Yii::app()->cache->set($cacheKey, $result, $this->cacheDuration);
        }

        return $result;
    }

    public function getSingle($tenantId, $id, $arguments = array()) {
        $cacheKey = APIBase::cacheKeyBuilder(get_class($this->model), $tenantId, $arguments, $id);

        // serve from cache if possible
        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }

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
            $result = $this->model->with($relations)->findByPk($id);
        } catch (CDbException $cdbE) {
            $result = "no_results";
        }


        if (!empty($result)) {
            Yii::app()->cache->set($cacheKey, $result, $this->cacheDuration);
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

    /**
     * Helper
     * build a unique key based on the model requested and arguments
     * @param string $prefix prefix - should be a unique name describing the resource requested
     * @param integer $tenantId - tenant id
     * @param array $arguments - API request arguments
     * @param integer $id - primary key (optional)
     * 
     */
    public static function cacheKeyBuilder($prefix, $tenantId, $arguments = array(), $id = null) {

        $string = $prefix . '_' . $tenantId . '_' . serialize($arguments);

        if ($id != null) {
            $string.= '_' . $id;
        }

        return $string;
    }

}