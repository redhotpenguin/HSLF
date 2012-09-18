<?php

class BallotItemCriteria {

    private $ballotItem;
    private $criteria;
    private $tableAlias;
    private $bindParams;
    private $sort;

    public function __construct() {

        $this->ballotItem = new BallotItem;
        $this->criteria = new CDbCriteria;
        $this->tableAlias = $this->ballotItem->getTableAlias(false, false);
        $this->bindParams = array();

        $this->sort = array(
            'defaultOrder' => $this->tableAlias . '.id ASC',
        );
        
        // set relations
        $this->setWith();


        //  This should be a parameter:
        // only find item with a endorser position different than np
        // $this->criteria->addCondition('position !=:position', 'AND');
        // $this->bindParams[':position'] = 'np';

        $this->criteria->addCondition("published='yes'", 'AND');
    }

    public function setTaxonomy($taxonomy, $taxonomyID) {

        if ($taxonomy == 'endorser') {

            if (!is_numeric($taxonomyID))
                return false;

            $this->criteria->addCondition('endorsers.id = :endorserID', 'AND');
            $this->bindParams[':endorserID'] = $taxonomyID;
        }
    }

    public function setState($state) {
        $this->criteria->addCondition('district.state_abbr=:stateAbbr', 'AND');
        $this->bindParams[':stateAbbr'] = $state;
    }

    public function setDistricts($codedDistricts) {
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

            $this->criteria->addCondition('district.type=:districtType' . $i . ' AND district.number=:districtNumber' . $i, $operator);

            $this->bindParams[":districtType{$i}"] = $districtType;
            $this->bindParams[":districtNumber{$i}"] = $districtNumber;

            ++$i;
        }
    }

    public function setOrder($orderBy, $order) {
        if ($this->ballotItem->hasAttribute($orderBy)) {
            if (strtoupper($order) == 'ASC' || strtoupper($order) == 'DESC')
                $this->criteria->order = "{$this->tableAlias}.{$orderBy} {$order}";
        }
    }

    public function setLimit($limit) {
        $this->criteria->limit = $limit;
    }
    
    public function setWith(array $with = null){
            if(isset($with))
                $this->criteria->with = $with;
            else
                $this->criteria->with =  array(
            'district',
            'recommendation',
            'electionResult',
            'ballotItemNews',
            'scorecards',
            'cards',
            'office',
            'party',
            'endorsers' => array(
                'together' => true,
                'joinType' => 'LEFT JOIN',
            ),
        );
        
    }

    public function search() {

        // bind parameters
        $this->criteria->params = $this->bindParams;
        
        //    print_r($this->criteria->toArray());

        $activeDataProvider = new CActiveDataProvider($this->ballotItem, array(
                    'criteria' => $this->criteria,
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