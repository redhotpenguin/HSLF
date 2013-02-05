<?php

class PushMessagesAPI extends APIBase {

    public function __construct() {
        parent::__construct(new PushMessage);
    }

    /**
     * override get list 
     */
    public function getList($tenantId, $arguments = array()) {

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

        return $criteria->search();
    }

}