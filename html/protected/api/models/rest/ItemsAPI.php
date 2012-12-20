<?php

class ItemsAPI implements IAPI {

    private $allIncludes = array('districts', 'organizations', 'recommendations', 'news', 'parties');
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
        $this->item = $this->item->with(array('district', 'recommendation', 'itemNews', 'party'))->findByPk($id);

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

        if (array_key_exists('organizations', $includeList)) {
            array_push($includes, 'organizations');
            $itemCriteria->addOrganizationRelation();
        }

        if (array_key_exists('recommendations', $includeList)) {
            array_push($includes, 'recommendations');
            $itemCriteria->addRecommendationRelation();
        }

        if (array_key_exists('news', $includeList)) {
            array_push($includes, 'news');
            $itemCriteria->addNewsRelation();
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
            'url' => $item->url,
            'personal_url' => $item->personal_url,
            'facebook_url' => $item->facebook_url,
            'twitter_handle' => $item->twitter_handle,
            'measure_number' => $item->measure_number,
            'friendly_name' => $item->friendly_name,
            'first_name' => $item->first_name,
            'last_name' => $item->last_name,
        );

        if (in_array('organizations', $includes)) {
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
                    'list_name' => $itemOrganizations->organization->list_name,
                    'slug' => $itemOrganizations->organization->slug,
                    'facebook_share' => $itemOrganizations->organization->facebook_share,
                    'twitter_share' => $itemOrganizations->organization->twitter_share,
                ));
                ++$i;
            }

            $wrapped_item['organizations'] = $organizations;
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

        if (in_array('parties', $includes)) {
            $wrapped_item['party'] = $item->party;
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
