<?php

class BallotItemCriteria extends CDbCriteria {

    private $ballotItem;
    private $tableAlias;
    private $bindParams;
    private $sort;

    public function __construct() {
        $this->ballotItem = new BallotItem;
        $this->tableAlias = $this->ballotItem->getTableAlias(false, false);
        $this->bindParams = array();

        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.id ASC',
        );

        $defaultRelations = array(
            'district',
            'recommendation',
            'electionResult',
            'ballotItemNews', // remove
            'scorecards', // remove
            'cards', // remove
            'office',
            'party',
        );


        $this->setRelations($defaultRelations);

        //  This should be a parameter:
        // only find item with a endorser position different than np
        // $this->criteria->addCondition('position !=:position', 'AND');
        // $this->bindParams[':position'] = 'np';

        $this->addCondition("published='yes'", 'AND');
    }

    public function setTaxonomy($taxonomy, $taxonomyID) {

        if ($taxonomy == 'endorser') {

            if (!is_numeric($taxonomyID))
                return false;


            $this->addCondition('endorsers.id = :endorserID', 'AND');
            $this->bindParams[':endorserID'] = $taxonomyID;

            // add relations
            $withEndorsers = array(
                'together' => true,
                'joinType' => 'LEFT JOIN',
            );

            $this->addRelation('endorsers', $withEndorsers);

        }
    }

    public function setState($state) {
        $this->addCondition('district.state_abbr=:stateAbbr', 'AND');
        $this->bindParams[':stateAbbr'] = $state;
    }

    public function setDistricts($codedDistricts) {
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

            $this->bindParams[":districtType{$i}"] = $districtType;
            $this->bindParams[":districtNumber{$i}"] = $districtNumber;

            ++$i;
        }
    }

    public function setOrder($orderBy, $order) {
        if ($this->ballotItem->hasAttribute($orderBy)) {
            if (strtoupper($order) == 'ASC' || strtoupper($order) == 'DESC')
                $this->order = "{$this->tableAlias}.{$orderBy} {$order}";
        }
    }

    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function setRelations(array $with = null) {
        $this->with = $with;
    }

    public function addRelation($relationName, $with = array()) {

        if (!empty($with))
            $this->with[$relationName] = $with;
        else
            array_push($this->with, $relationName);
    }

    public function search() {

        // bind parameters
        $this->params = $this->bindParams;

        // set relations
        $this->with = $this->with;

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

}