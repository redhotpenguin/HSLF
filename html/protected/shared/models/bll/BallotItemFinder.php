<?php

class BallotItemFinder extends ModelFinder {

    public function __construct() {
        $this->setRelations(array('district', 'recommendation', 'electionResult'));
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

    public function setRunningOnly() {
        $this->addCondition('next_election_date', 'current_date', ModelFinder::GREATER_THAN); // manage > 
        $this->addParameter('current_date', date('Y-m-d H:i:s'));
    }

    public function setPriority($priority) {
        $this->addCondition('priority', 'priority', ModelFinder::EQUAL);
        $this->addParameter('priority', $priority);
    }

    public function setPublishedYear($year) {
        $this->addCondition('date_published', 'year_start', ModelFinder::GREATER_THAN);
        $this->addParameter('year_start', $year . '-01-01 00:00:00');

        $this->addCondition('date_published', 'year_end', ModelFinder::LESSER_THAN);
        $this->addParameter('year_end', $year . '-12-31 23:59:59');
    }

    public function orderByHighestPriority(){
        $this->setOrder('priority', ModelFinder::DESCENDANT);
    }

     public function orderByLowestPriority(){
        $this->setOrder('priority', ModelFinder::ASCENDANT);
    }

}