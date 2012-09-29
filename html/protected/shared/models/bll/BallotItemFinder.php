<?php

class BallotItemFinder extends ModelFinder {

    private $table_alias;

    public function __construct() {

        $ballot_item = new BallotItem;

        $this->table_alias = $ballot_item->getTableAlias(false, false);

        parent::__construct($ballot_item);
        $this->setRelations(array('district', 'recommendation', 'electionResult', 'ballotItemNews', 'scorecards', 'cards', 'office'));
    }

    public function setDistrictIds($district_ids) {
        $this->addAttribute('district_id', $district_ids);
    }

    public function setParty($party) {
        $this->addAttribute('party', $party);
    }

    public function setPublished($published) {
        $this->addAttribute('published', $published);
    }

    public function setUrl($url) {
        $this->addAttribute('url', $url);
    }

    public function setRunningOnly() {
        $this->addCondition('next_election_date', 'current_date', ModelFinder::GREATER_THAN);
        $this->addParameter('current_date', date('Y-m-d'));
    }

    public function setNullElectionDate() {
        $this->addCondition('next_election_date', '', ' ISNULL ', true);
    }

    public function setPublishedYear($year) {
        $this->addCondition($this->table_alias . '.date_published', 'year_start', ModelFinder::GREATER_THAN);
        $this->addParameter('year_start', $year . '-01-01 00:00:00');

        $this->addCondition($this->table_alias . '.date_published', 'year_end', ModelFinder::LESSER_THAN);
        $this->addParameter('year_end', $year . '-12-31 23:59:59');
    }



    public function orderByItem($order = ModelFinder::ASCENDANT) {
        if ($this->isValidOrder($order))
            $this->setOrder('item', $order);
    }

    public function orderByMeasureNumber($order = ModelFinder::ASCENDANT) {
        if ($this->isValidOrder($order))
            $this->setOrder('measure_number', $order);
    }

    public function setName($name, $operator = ModelFinder::EQUAL) {
        $this->addCondition('item', 'name', $operator);

        if ($operator == ModelFinder::ILIKE || $operator == ModelFinder::LIKE) {
            $this->addParameter('name', "%$name%");
        }else
            $this->addParameter('name', "$name");
    }

}