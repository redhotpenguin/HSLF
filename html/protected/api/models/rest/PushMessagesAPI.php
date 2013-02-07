<?php

class PushMessagesAPI extends APIBase {

    public function __construct() {
        parent::__construct(new PushMessage);
        $this->cacheDuration = Yii::app()->params->short_cache_duration;
    }

    /**
     * override get list 
     */
    public function getList($tenantId, $arguments = array()) {

        $cacheKey = APIBase::cacheKeyBuilder($this->model, $tenantId, $arguments);

        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }

        $criteria = new PushMessageCriteria($this->model);

        // check if relationships are set
        if (isset($arguments['relations']) && !empty($arguments['relations'])) {
            $relations = explode(',', $arguments['relations']);
            $criteria->setRelations($relations);
        }


        // filter by tags
        if (isset($arguments['tags']) && !empty($arguments['tags'])) {
            $tags = explode(',', $arguments['tags']);
            $criteria->setTags($tags);
        }

        // limit results
        if (isset($arguments['limit']) && is_numeric($arguments['limit'])) {
            $criteria->setLimit($arguments['limit']);
        }

        $result = $criteria->search();
        if (!empty($result))
            Yii::app()->cache->set($cacheKey, $result, $this->cacheDuration);

        return $result;
    }

}