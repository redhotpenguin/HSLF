<?php

class BallotItemCriteria extends CDbCriteria {

    private $ballotItem;
    private $tableAlias;
    private $sort;

    /**
     * BallotItemCriteria - extends CDbCriteria
     */
    public function __construct() {
        $this->ballotItem = new BallotItem;
        $this->tableAlias = $this->ballotItem->getTableAlias(false, false);


        // order by descending ID by default.
        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.id DESC',
        );

        // set no default relations
        $this->with = array();
    }

    /**
     * Set the taxonomy that a ballot item lives in
     * @param string $taxonomy - taxonomy name
     * @param integer $taxonomyID - taxonomy id
     */
    public function setTaxonomy($taxonomy, $taxonomyID) {

        if ($taxonomy == 'endorser') {

            if (!is_numeric($taxonomyID))
                return false;


            $this->addCondition('endorsers.id = :endorserID ', 'AND');
            $this->addCondition('position !=:position', 'AND');

            $this->params[':endorserID'] = $taxonomyID;
            $this->params[':position'] = 'np';

            $this->addEndorserRelation();
        }
    }

    /**
     * Set the state to look in
     * @param string $stateAbbr - state abbreviation
     */
    public function setState($stateAbbr) {
        $this->addCondition('district.state_abbr=:stateAbbr', 'AND');
        $this->addRelation('district');
        $this->params[':stateAbbr'] = $stateAbbr;
    }

    /**
     * Set the districts to look in
     * @param array $codedDistricts - encoded districts
     */
    public function setDistricts(array $codedDistricts) {
        // todo: add locality
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

            $this->addCondition('district.type=:districtType' . $i . ' AND district.number=:districtNumber' . $i, $operator);

            $this->params[":districtType{$i}"] = $districtType;
            $this->params[":districtNumber{$i}"] = $districtNumber;

            ++$i;
        }
    }

    /**
     * Add a condition based on the ballot item published status
     * @param string $published - yes or no
     */
    public function setPublishedStatus($published) {
        $this->addCondition("published=:published", 'AND');
        $this->params[":published"] = $published;
    }

    /**
     * Order results
     * @param string $orderBy - fields to order by
     * @param string $order - order value ( ASC/DESC)
     */
    public function setOrder($orderBy, $order) {
        if ($this->ballotItem->hasAttribute($orderBy)) {
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
     * Search ballot items based on the criteria
     * @return return ballot items
     */
    public function search() {
      //  print_r($this->toArray());
        $activeDataProvider = new CActiveDataProvider($this->ballotItem, array(
                    'criteria' => $this,
                    'sort' => $this->sort,
                ));

        $activeDataProvider->pagination = false;

        try {
            $ballotItems = $activeDataProvider->getData();
        } catch (CDbException $cdbE) {
            echo $cdbE->getMessage(); // debug
            $ballotItems = false;
        }

        return $ballotItems;
    }

    /**
     * Set the relation for scorecards
     */
    public function addDistrictRelation() {
        $this->addRelation('district');
    }

    /**
     * Set the relation for scorecards
     */
    public function addScorecardRelation() {
        $this->addRelation('scorecards');
        $this->addRelation('cards');
    }

    /**
     * Set the relation for news
     */
    public function addNewsRelation() {
        $this->addRelation('ballotItemNews');
    }

    /**
     * Set the relation for endorsers
     */
    public function addEndorserRelation() {
        $withEndorsers = array(
            'together' => true,
            'joinType' => 'LEFT JOIN',
        );

        $this->addRelation('endorsers', $withEndorsers);
    }

    /**
     * Set the relation for recommendations
     */
    public function addRecommendationRelation() {
        $this->addRelation('recommendation');
    }

    /**
     * Set the relation for election results
     */
    public function addElectionResultRelation() {
        $this->addRelation('electionResult');
    }

    /**
     * Set the relation for offices
     */
    public function addOfficeRelation() {
        $this->addRelation('office');
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

        if (!$this->ballotItem->hasAttribute($attribute))
            return false;

        $condition = "$attribute=:$attribute";

        $this->addCondition("{$this->tableAlias}.{$condition}", $operator);
        $this->params[":$attribute"] = $value;
    }

    public function addAllRelations() {
        $this->with = array(); // remove existing relarions
        $this->addDistrictRelation();
        $this->addScorecardRelation();
        $this->addEndorserRelation();
        $this->addRecommendationRelation();
        $this->addElectionResultRelation();
        $this->addNewsRelation();
        $this->addOfficeRelation();
        $this->addPartyRelation();
    }

}