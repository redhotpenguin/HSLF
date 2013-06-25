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
        $criteria = new PushMessageCriteria($this->model);

        $criteria->addCondition('recipient_type !=:recipient', 'AND');
        $criteria->params[":recipient"] = 'single';


        // check if relationships are set
        if (isset($arguments['relations']) && !empty($arguments['relations'])) {
            $relations = explode(',', $arguments['relations']);
            $criteria->setRelations($relations);
        }


        // filter by tags
        if (isset($arguments['tags']) && !empty($arguments['tags'])) {
            $tags = explode(',', $arguments['tags']);
            $criteria->setTags($tags);

            $criteria->addCondition('recipient_type=:recipient_type', 'OR');
            $criteria->params[":recipient_type"] = 'broadcast';
        }

        // limit results
        if (isset($arguments['limit']) && is_numeric($arguments['limit'])) {
            $criteria->setLimit($arguments['limit']);
        }

        // uncomment the following lines to see the 'query' structure
        //  echo '<pre>';
        //print_r($criteria->toArray());
         //echo '</pre>';

        return $criteria->search();
    }

}