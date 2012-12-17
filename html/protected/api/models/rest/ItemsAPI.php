<?php

class ItemsAPI implements IAPI {

    private $allIncludes = array('districts', 'scorecards', 'endorsers', 'recommendations', 'news', 'electionResults', 'offices', 'parties');
    private $item;

    public function __construct() {
        $this->item = new Item();
    }

    /**
     * return a list of items
     * @param  array $arguments API Get Parameters
     * @return array wrapped items
     */
    public function getList($tenantId, $arguments = array()) {
        $this->item->sessionTenantId = $tenantId;
        $includes = array();

        $itemCriteria = new ItemCriteria($this->item);

        if (isset($arguments['address'])) {
            $districtIds = $this->retrieveDistrictIdsByAddress($arguments['address']);
            if (empty($districtIds)) {
                return false;
            }
            $itemCriteria->setDistrictIds($districtIds);
        }

        if (isset($arguments['lat']) && isset($arguments['long'])) {
            $districtIds = $this->retrieveDistrictIdsByLatLong($arguments['lat'], $arguments['long']);
            if (empty($districtIds)) {
                return false;
            }
            $itemCriteria->setDistrictIds($districtIds);
        }


        $this->criteriaBuilder($itemCriteria, $arguments);

        if (isset($arguments['includes'])) {

            if ($arguments['includes'] == 'all') {
                $includes = $this->allIncludes;
                $itemCriteria->addAllRelations();
            } else {
                $includes = explode(',', $arguments['includes']);
                $includes = $this->includeParser($itemCriteria, $includes);
            }
        }

        $items = $itemCriteria->search();

        if ($items)
            return $this->itemsWrapper($items, $includes);
        else
            return false;
    }

    /**
     * Retrieve district ids using an address using a geocoding service
     * @param string $address address
     */
    private function retrieveDistrictIdsByAddress($address) {
        $geoCodingClientProvider = new GeoCodingClientProvider($this->item->sessionTenantId);

        $geoCodingClient = $geoCodingClientProvider->getGeoCodingClient('cicero');

        $districts = $geoCodingClient->getDistrictIdsByAddress($address);

        return ($districts) ? $districts : array();
    }

    /**
     * Retrieve district ids given a lat/long using a geocoding service
     * @param $lat latitude
     * @param $long longitude
     */
    private function retrieveDistrictIdsByLatLong($lat, $long) {
        $geoCodingClientProvider = new GeoCodingClientProvider($this->item->sessionTenantId);

        $geoCodingClient = $geoCodingClientProvider->getGeoCodingClient('cicero');

        $districts = $geoCodingClient->getDistrictIdsByLatLong($lat, $long);

        return ($districts) ? $districts : array();
    }

    /**
     * get a single item with relations
     * @param integer $id  item id
     * @todo Refactor this function to use ItemCriteria?
     */
    public function getSingle($tenantId, $id, $arguments = array()) {
        $this->item->sessionTenantId = $tenantId;
        // todo: find better way to do this
        $this->item = $this->item->with(array('district', 'recommendation', 'electionResult', 'itemNews', 'scorecards', 'cards', 'office', 'party'))->findByPk($id);

        if ($this->item != false)
            $result = $this->itemWrapper($this->item, $this->allIncludes);
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

            $itemCriteria->setState($arguments['state']);

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
    }

    private function includeParser(ItemCriteria &$itemCriteria, $includeList) {
        $includes = array();

        $includeList = array_map('trim', $includeList); // remove accidental white spaces
        // switch keys and indexes, so we can use the index as a lookup
        $includeList = array_flip($includeList);

        if (array_key_exists('districts', $includeList)) {
            array_push($includes, 'districts');
            $itemCriteria->addDistrictRelation();
        }

        if (array_key_exists('scorecards', $includeList)) {
            array_push($includes, 'scorecards');
            $itemCriteria->addScorecardRelation();
        }

        if (array_key_exists('endorsers', $includeList)) {
            array_push($includes, 'endorsers');
            $itemCriteria->addEndorserRelation();
        }

        if (array_key_exists('recommendations', $includeList)) {
            array_push($includes, 'recommendations');
            $itemCriteria->addRecommendationRelation();
        }

        if (array_key_exists('electionResults', $includeList)) {
            array_push($includes, 'electionResults');
            $itemCriteria->addElectionResultRelation();
        }

        if (array_key_exists('news', $includeList)) {
            array_push($includes, 'news');
            $itemCriteria->addNewsRelation();
        }

        if (array_key_exists('offices', $includeList)) {
            array_push($includes, 'offices');
            $itemCriteria->addOfficeRelation();
        }

        if (array_key_exists('parties', $includeList)) {
            array_push($includes, 'parties');
            $itemCriteria->addPartyRelation();
        }

        return $includes;
    }

    /**
     * return a wrapped item array
     * @param Item $item  item
     * @return array wrapped item
     */
    private function itemWrapper(Item $item, $includes = array()) {
        $scorecards = array();
        $endorsers = array();
        $i = 0;

        $wrapped_item = array(
            'id' => $item->id,
            'item' => $item->item,
            'item_type' => $item->item_type,
            'next_election_date' => $item->next_election_date,
            'detail' => $item->detail,
            'date_published' => $item->date_published,
            'image_url' => $item->image_url,
            'url' => $item->url,
            'personal_url' => $item->personal_url,
            'score' => $item->score,
            'facebook_url' => $item->facebook_url,
            'facebook_share' => $item->facebook_share,
            'twitter_handle' => $item->twitter_handle,
            'twitter_share' => $item->twitter_share,
            'hold_office' => $item->hold_office,
            'measure_number' => $item->measure_number,
            'friendly_name' => $item->friendly_name,
            'keywords' => $item->keywords,
        );

        if (in_array('endorsers', $includes)) {
            $i = 0;
            $endorsers = array();


            foreach ($item->itemEndorsers as $itemEndorsers) {

                array_push($endorsers, array(
                    'id' => $itemEndorsers->endorser->id,
                    'position' => $itemEndorsers->position,
                    'name' => $itemEndorsers->endorser->name,
                    'description' => $itemEndorsers->endorser->description,
                    'website' => $itemEndorsers->endorser->website,
                    'image_url' => $itemEndorsers->endorser->image_url,
                    'display_name' => $itemEndorsers->endorser->display_name,
                    'list_name' => $itemEndorsers->endorser->list_name,
                    'slug' => $itemEndorsers->endorser->slug,
                    'facebook_share' => $itemEndorsers->endorser->facebook_share,
                    'twitter_share' => $itemEndorsers->endorser->twitter_share,
                ));
                ++$i;
            }

            $wrapped_item['endorsers'] = $endorsers;
        }

        if (in_array('scorecards', $includes)) {
            $scorecards = array();
            $i = 0;
            foreach ($item->scorecards as $scorecard) {
                array_push($scorecards, array(
                    'id' => $scorecard->id,
                    'name' => $item->cards[$i]->name,
                    'description' => $item->cards[$i]->description,
                    'vote' => $scorecard->vote->name,
                    'vote_icon' => $scorecard->vote->icon,
                ));
                ++$i;
            }

            $wrapped_item['scorecards'] = $scorecards;
        }

        if (in_array('districts', $includes)) {
            $wrapped_item['districts'] = array($item->district);
        }

        if (in_array('news', $includes)) {
            $wrapped_item['news'] = $item->itemNews;
        }

        if (in_array('recommendations', $includes)) {
            $wrapped_item['recommendation'] = $item->recommendation;
        }

        if (in_array('electionResults', $includes)) {
            $wrapped_item['electionResult'] = $item->electionResult;
        }


        if (in_array('parties', $includes)) {
            $wrapped_item['party'] = $item->party;
        }


        if (in_array('offices', $includes)) {
            $wrapped_item['office'] = $item->office;
        }

        return $wrapped_item;
    }

    /**
     * return an array of wrapped items
     * @param $items array of Item Objects
     * @return array array of wrapped items
     */
    private function itemsWrapper(array $items, $includes = array()) {
        $wrapped_items = array();
        foreach ($items as $item)
            array_push($wrapped_items, $this->itemWrapper($item, $includes));

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
