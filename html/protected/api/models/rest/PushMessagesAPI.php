<?php

class PushMessagesAPI extends APIBase {

    public function __construct() {
        parent::__construct(new PushMessage);
    }

    /**
     * override get list 
     */
    public function getList($tenantId, $arguments = array()) {
        $cacheKey = md5(serialize($arguments) . $tenantId);

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
            Yii::app()->cache->set($cacheKey, $result, Yii::app()->params->cache_duration);

        return $result;
    }

}