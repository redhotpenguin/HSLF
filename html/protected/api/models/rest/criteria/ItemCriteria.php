<?php

class ItemCriteria extends CDbCriteria {

    private $item;
    private $tableAlias;
    private $sort;

    /**
     * ItemCriteria - extends CDbCriteria
     */
    public function __construct(Item $item) {
        $this->item = $item;
        $this->tableAlias = $this->item->getTableAlias(false, false);


        // order by descending ID by default.
        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.id DESC',
        );

        // set no default relations
        $this->with = array();
    }

    /**
     * Set the taxonomy that an item lives in
     * @param string $taxonomy - taxonomy name
     * @param integer $taxonomyID - taxonomy id
     */
    public function setTaxonomy($taxonomy, $taxonomyID) {

        if ($taxonomy == 'organization') {

            if (!is_numeric($taxonomyID))
                return false;


            $this->addCondition('organizations.id = :organizationID ', 'AND');
            $this->addCondition('position !=:position', 'AND');

            $this->params[':organizationID'] = $taxonomyID;
            $this->params[':position'] = 'np';

            $this->addOrganizationRelation();
        }
    }

    /**
     * Set the state to look in
     * @param string $stateAbbr - state abbreviation
     */
    public function setState($stateAbbr) {
        // @todo: optimize this
        $state = State::model()->findByAttributes(array("abbr" => $stateAbbr));
        if (!$state)
            return false;


        $this->addCondition('district.state_id=:state_id', 'AND');
        $this->addRelation('district');
        $this->params[':state_id'] = $state->id;

        return true;
    }

    /**
     * Set the districts to look in -- must be executed after setState()
     * @param array $codedDistricts - encoded districts
     * @param string $stateAbbr - stateabbr
     */
    public function setDistricts(array $codedDistricts) {
        $i = 0;
        foreach ($codedDistricts as $codedDistrict) {
            $d = explode('/', $codedDistrict);
            if (!isset($d[0])) // type
                continue;

            $districtType = $d[0];

            if (isset($d[1])) // district number
                $districtNumber = $d[1];
            else
                $districtNumber = "";



            if ($i == 0)
                $operator = 'AND';
            else
                $operator = 'OR';

            $condition = 'district.state_id=:state_id AND district.type=:districtType' . $i . ' AND district.number=:districtNumber' . $i;

            if (isset($d[2])) { // locality
                $condition.=" AND district.locality=:districtLocality" . $i;
                $this->params[":districtLocality{$i}"] = $d[2];
            }

            $this->addCondition($condition, $operator);

            $this->params[":districtType{$i}"] = $districtType;
            $this->params[":districtNumber{$i}"] = $districtNumber;

            ++$i;
        }
    }

    public function setDistrictIds(array $districtIds) {
        $i = 0;

        foreach ($districtIds as $districtId) {
            if ($i == 0)
                $operator = 'AND';
            else
                $operator = 'OR';

            $this->addCondition('district_id=:districtId' . $i, $operator);
            $this->params[":districtId{$i}"] = $districtId;
            ++$i;
        }
    }

    /**
     * Add a condition based on the item published status
     * @param string $published - yes or no
     */
    public function setPublishedStatus($published) {
        $this->addCondition($this->tableAlias . ".published=:published", 'AND');
        $this->params[":published"] = $published;
    }

    /**
     * Order results
     * @param string $orderBy - fields to order by
     * @param string $order - order value ( ASC/DESC)
     */
    public function setOrder($orderBy, $order) {
        if ($this->item->hasAttribute($orderBy)) {
            if (strtoupper($order) == 'ASC' || strtoupper($order) == 'DESC')
                $this->order = "{$this->tableAlias}.{$orderBy} {$order}";
        }
    }

    /**
     * Limit results ( bug )
     * @param integer $limit - limit number
     */
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    /**
     * Set relations
     * @param array $relations - see CDBCriteria (with)
     */
    public function setRelations(array $relations = null) {
        $this->with = $relations;
    }

    /**
     * Add a relation
     * @param string  $relation - see CDBCriteria (with)
     */
    public function addRelation($relationName, $with = array()) {

        // make sure the relation is not already loaded
        if (in_array($relationName, $this->with))
            return false;

        if (!empty($with))
            $this->with[$relationName] = $with;
        else
            array_push($this->with, $relationName);
    }

    /**
     * Search  items based on the criteria
     * @return return  items
     */
    public function search() {
     //   echo '<pre>';
      //  print_r($this->toArray());
       // die;

        $activeDataProvider = new CActiveDataProvider($this->item, array(
                    'criteria' => $this,
                    'sort' => $this->sort,
                ));

        $activeDataProvider->pagination = false;

        try {
            $items = $activeDataProvider->getData();
        } catch (CDbException $cdbE) {
               echo $cdbE->getMessage(); // debug
            $items = false;
        }



        return $items;
    }

    /**
     * Set the relation for districts
     */
    public function addDistrictRelation() {
        $this->addRelation('district');
    }

    /**
     * Set the relation for news
     */
    public function addNewsRelation() {
        $this->addRelation('itemNews');
    }

    /**
     * Set the relation for organizaitons
     */
    public function addOrganizationRelation() {
        $withOrganizations = array(
            'together' => true,
            'joinType' => 'LEFT JOIN',
        );

        $this->addRelation('organizations', $withOrganizations);
    }

    /**
     * Set the relation for recommendations
     */
    public function addRecommendationRelation() {
        $this->addRelation('recommendation');
    }

    /**
     * Set the relation for parties
     */
    public function addPartyRelation() {
        $this->addRelation('party');
    }

    /**
     * Add a condition based on a model attribute
     * @param string $attribute - model attribute
     * @param string $value - field value
     * string string $operator - logic operator
     */
    public function addAttributeCondition($attribute, $value, $operator = 'AND') {

        if (!$this->item->hasAttribute($attribute))
            return false;

        $condition = "$attribute=:$attribute";

        $this->addCondition("{$this->tableAlias}.{$condition}", $operator);
        $this->params[":$attribute"] = $value;
    }

    public function addAllRelations() {
        $this->with = array(); // remove existing relarions
        $this->addDistrictRelation();
        $this->addOrganizationRelation();
        $this->addRecommendationRelation();
        $this->addNewsRelation();
        $this->addPartyRelation();
    }

}