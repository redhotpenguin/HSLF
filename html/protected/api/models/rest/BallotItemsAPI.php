<?php

class BallotItemsAPI implements IAPI {

    private $allIncludes = array('districts', 'organizations', 'recommendations', 'news', 'party');
    private $item;
    private $cacheDuration;

    public function __construct() {
        $this->item = new BallotItem();
        $this->cacheDuration = Yii::app()->params->normal_cache_duration;
    }

    /**
     * return a list of items
     * @param  array $arguments API Get Parameters
     * @return array wrapped items
     */
    public function getList($tenantId, $arguments = array()) {
        // build a unique cache key
        $cacheKey = APIBase::cacheKeyBuilder('item', $tenantId, $arguments);


        // serve from cache?
        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }

        $relations = array();

        $itemCriteria = new ItemCriteria($this->item);

        // wrong parameter passed
        if (( $message = $this->criteriaBuilder($itemCriteria, $arguments) ) !== true) {
            return $message;
        }

        if (isset($arguments['relations'])) {

            if ($arguments['relations'] == 'all') {
                $relations = $this->allIncludes;
                $itemCriteria->addAllRelations();
            } else {
                $relations = explode(',', $arguments['relations']);
                $relations = $this->relationParser($itemCriteria, $relations);
            }
        }

        $items = $itemCriteria->search();

        if ($items) {
            $result = $this->itemsWrapper($items, $relations);
            Yii::app()->cache->set($cacheKey, $result, $this->cacheDuration);
            return $result;
        }
        else
            return false;
    }

    /**
     * get a single item with relations
     * @param integer $id  item id
     * @todo Refactor this function to use ItemCriteria?
     */
    public function getSingle($tenantId, $id, $arguments = array()) {
        $cacheKey = APIBase::cacheKeyBuilder('item', $tenantId, $arguments, $id);

        // serve from cache if possible
        if (($r = Yii::app()->cache->get($cacheKey)) == true) {
            return $r;
        }
        


        // todo: find better way to do this
        $item = $this->item->with(array('district', 'recommendation', 'party'))->findByPk($id);
       

        if ($this->item != false) {
            $result = $this->itemWrapper($item, $this->allIncludes);
            Yii::app()->cache->set($cacheKey, $result, $this->cacheDuration);
        }
        else
            $result = false;


        return $result;
    }

    /**
     * Set up a ItemCriteria based on an array of arguments
     * @param ItemCriteria &$itemCriteria reference to a itemcriteria object
     * @param array $arguments - array of arguments
     */
    private function criteriaBuilder(ItemCriteria &$itemCriteria, $arguments) {
        $itemCriteria->setPublishedStatus('yes');

        if (isset($arguments['state'])) {

            if (!$itemCriteria->setState($arguments['state'])) {
                return 'invalid state';
            }

            if (isset($arguments['districts'])) {
                $itemCriteria->setDistricts(explode(',', $arguments['districts']));
            }
        }


        if (isset($arguments['orderBy']) && isset($arguments['order'])) {
            $itemCriteria->setOrder($arguments['orderBy'], $arguments['order']);
        }

        if (isset($arguments['limit']) && is_numeric($arguments['limit'])) {
            $itemCriteria->setLimit($arguments['limit']);
        }

        if (isset($arguments['taxonomy']) && isset($arguments['taxonomyID'])) {
            $itemCriteria->setTaxonomy($arguments['taxonomy'], $arguments['taxonomyID']);
        }

        if (isset($arguments['field']) && isset($arguments['fieldValue'])) {
            $itemCriteria->addAttributeCondition($arguments['field'], $arguments['fieldValue'], 'AND');
        }


        return true;
    }

    private function relationParser(ItemCriteria &$itemCriteria, $relationList) {
        $relations = array();

        $relationList = array_map('trim', $relationList); // remove accidental white spaces
        // switch keys and indexes, so we can use the index as a lookup
        $relationList = array_flip($relationList);

        if (array_key_exists('districts', $relationList)) {
            array_push($relations, 'districts');
            $itemCriteria->addDistrictRelation();
        }

        if (array_key_exists('organizations', $relationList)) {
            array_push($relations, 'organizations');
            $itemCriteria->addOrganizationRelation();
        }

        if (array_key_exists('recommendations', $relationList)) {
            array_push($relations, 'recommendations');
            $itemCriteria->addRecommendationRelation();
        }

        if (array_key_exists('news', $relationList)) {
            array_push($relations, 'news');
           // $itemCriteria->addNewsRelation(); // eager loading creates a bug whith indirect multitenancy
        }

        if (array_key_exists('party', $relationList)) {
            array_push($relations, 'party');
            $itemCriteria->addPartyRelation();
        }

        return $relations;
    }

    /**
     * return a wrapped item array
     * @param Item $item  item
     * @return array wrapped item
     */
    private function itemWrapper(BallotItem $item, $relations = array()) {
        $organizations = array();
        $i = 0;

        $wrapped_item = array(
            'id' => $item->id,
            'item' => $item->item,
            'item_type' => $item->item_type,
            'next_election_date' => $item->next_election_date,
            'detail' => $item->detail,
            'date_published' => $item->date_published,
            'image_url' => $item->image_url,
            'slug' => $item->slug,
            'website' => $item->website,
            'facebook_url' => $item->facebook_url,
            'twitter_handle' => $item->twitter_handle,
            'measure_number' => $item->measure_number,
            'friendly_name' => $item->friendly_name,
            'first_name' => $item->first_name,
            'last_name' => $item->last_name,
        );

        if (in_array('organizations', $relations)) {
            $i = 0;
            $organizations = array();


            foreach ($item->itemOrganizations as $itemOrganizations) {

                array_push($organizations, array(
                    'id' => $itemOrganizations->organization->id,
                    'position' => $itemOrganizations->position,
                    'name' => $itemOrganizations->organization->name,
                    'description' => $itemOrganizations->organization->description,
                    'website' => $itemOrganizations->organization->website,
                    'image_url' => $itemOrganizations->organization->image_url,
                    'display_name' => $itemOrganizations->organization->display_name,
                    'slug' => $itemOrganizations->organization->slug,
                    'facebook_url' => $itemOrganizations->organization->facebook_url,
                    'twitter_handle' => $itemOrganizations->organization->twitter_handle,
                ));
                ++$i;
            }

            $wrapped_item['organizations'] = $organizations;
        }

        if (in_array('districts', $relations)) {
            $wrapped_item['districts'] = array($item->district);
        }

        if (in_array('news', $relations)) {
            $wrapped_item['news'] = $item->itemNews;
        }

        if (in_array('recommendations', $relations)) {
            $wrapped_item['recommendation'] = $item->recommendation;
        }

        if (in_array('party', $relations)) {
            $wrapped_item['party'] = $item->party;
        }

        return $wrapped_item;
    }

    /**
     * return an array of wrapped items
     * @param $items array of Item Objects
     * @return array array of wrapped items
     */
    private function itemsWrapper(array $items, $relations = array()) {
        $wrapped_items = array();
        foreach ($items as $item)
            array_push($wrapped_items, $this->itemWrapper($item, $relations));

        return $wrapped_items;
    }

    public function requiresAuthentification() {
        return false;
    }

    public function create($tenantId, $arguments = array()) {
        return "operation not supported";
    }

    public function update($tenantId, $id, $arguments = array()) {
        return "operation not supported";
    }

}
