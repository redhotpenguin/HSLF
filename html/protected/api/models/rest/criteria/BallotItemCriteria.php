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

        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.id ASC',
        );

        $defaultRelations = array(
            'district',
            'recommendation',// todo: remove from defaultRelation
            'electionResult',// todo: remove from defaultRelation
            'office',// todo: remove from defaultRelation
            'party', // todo: remove from defaultRelation
        );

        $this->setRelations($defaultRelations);
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


            $this->addCondition('endorsers.id = :endorserID', 'AND');
            $this->params[':endorserID'] = $taxonomyID;

            // add relations
            $this->addEndorserRelation();
        }
    }

    /**
     * Set the state to look in
     * @param string $stateAbbr - state abbreviation
     */
    public function setState($stateAbbr) {
        $this->addCondition('district.state_abbr=:stateAbbr', 'AND');
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
        // print_r($this->toArray());

        $activeDataProvider = new CActiveDataProvider($this->ballotItem, array(
                    'criteria' => $this,
                    'sort' => $this->sort,
                ));

        $activeDataProvider->pagination = false;

        try {
            $ballotItems = $activeDataProvider->getData();
        } catch (CDbException $cdbE) {
            echo $cdbE->getMessage();
            $ballotItems = false;
        }

        return $ballotItems;
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

}