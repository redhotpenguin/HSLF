<?php

class BallotItemsAPI extends APIBase implements IAPI {

    public function getList($arguments = array()) {

        $ballotItemCriteria = new BallotItemCriteria();
        $ballotItemCriteria->setPublishedStatus('yes');


        $includes = array();

        if (isset($arguments['state'])) {

            $ballotItemCriteria->setState($arguments['state']);

            if (isset($arguments['districts'])) {
                $ballotItemCriteria->setDistricts(explode(',', $arguments['districts']));
            }
        }

        if (isset($arguments['orderBy']) && isset($arguments['order'])) {
            $ballotItemCriteria->setOrder($arguments['orderBy'], $arguments['order']);
        }

        if (isset($arguments['limit']) && is_numeric($arguments['limit'])) {
            $ballotItemCriteria->setLimit($arguments['limit']);
        }

        if (isset($arguments['taxonomy']) && isset($arguments['taxonomyID'])) {
            $ballotItemCriteria->setTaxonomy($arguments['taxonomy'], $arguments['taxonomyID']);
        }

        if (isset($arguments['includes'])) {
            $include_list = explode(',', $arguments['includes']);
            $include_list = array_map('trim', $include_list); // remove accidental white spaces

            if (in_array('news', $include_list)) {
                array_push($includes, 'news');
                $ballotItemCriteria->addNewsRelation();
            }

            if (in_array('scorecards', $include_list)) {
                array_push($includes, 'scorecards');
                $ballotItemCriteria->addScorecardRelation();
            }

            if (in_array('endorsers', $include_list)) {
                array_push($includes, 'endorsers');
                $ballotItemCriteria->addEndorserRelation();
            }
        }

        $ballotItems = $ballotItemCriteria->search();

        if ($ballotItems)
            return $this->ballotItemsWrapper($ballotItems, $includes);
        else
            return false;
    }

    public function getSingle($id) {

        $ballot_item = BallotItem::model()->with(array('district', 'recommendation', 'electionResult', 'ballotItemNews', 'scorecards', 'cards', 'office', 'party'))->findByPk($id);



        //  $includes = array('scorecards', 'endorsers');

        $includes = array('endorsers');

        if ($ballot_item != false)
            $result = $this->ballotItemWrapper($ballot_item, $includes);
        else
            $result = false;

        return $result;
    }

    /**
     * return a wrapped ballot array
     * @param $ballot BallotItem ballot item
     * @return array wrapped ballot
     */
    private function ballotItemWrapper(BallotItem $ballot_item, $includes = array()) {
        $scorecards = array();
        $endorsers = array();
        $i = 0;

        $wrapped_ballot_item = array(
            'id' => $ballot_item->id,
            'item' => $ballot_item->item,
            'item_type' => $ballot_item->item_type,
            'recommendation' => $ballot_item->recommendation,
            'next_election_date' => $ballot_item->next_election_date,
            'district' => $ballot_item->district,
            'priority' => $ballot_item->priority,
            'detail' => $ballot_item->detail,
            'date_published' => $ballot_item->date_published,
            'party' => $ballot_item->party,
            'image_url' => $ballot_item->image_url,
            'electionResult' => $ballot_item->electionResult,
            'url' => $ballot_item->url,
            'personal_url' => $ballot_item->personal_url,
            'score' => $ballot_item->score,
            'office' => $ballot_item->office,
            'district' => $ballot_item->district,
            'facebook_url' => $ballot_item->facebook_url,
            'facebook_share' => $ballot_item->facebook_share,
            'twitter_handle' => $ballot_item->twitter_handle,
            'twitter_share' => $ballot_item->twitter_share,
            'hold_office' => $ballot_item->hold_office,
            'measure_number' => $ballot_item->measure_number,
            'friendly_name' => $ballot_item->friendly_name,
        );

        if (in_array('endorsers', $includes)) {
            $i = 0;
            $endorsers = array();

            foreach ($ballot_item->ballotItemEndorsers as $ballotItemEndorsers) {
                
            }


            foreach ($ballot_item->ballotItemEndorsers as $ballotItemEndorsers) {

                array_push($endorsers, array(
                    'endorser_id' => $ballotItemEndorsers->endorser->id,
                    'position' => $ballotItemEndorsers->position,
                    'name' => $ballotItemEndorsers->endorser->name,
                    'description' => $ballotItemEndorsers->endorser->description,
                    'website' => $ballotItemEndorsers->endorser->website,
                    'image_url' => $ballotItemEndorsers->endorser->image_url,
                ));
                ++$i;
            }

            $wrapped_ballot_item['endorsers'] = $endorsers;
        }

        if (in_array('scorecards', $includes)) {
            $scorecards = array();
            $i = 0;
            foreach ($ballot_item->scorecards as $scorecard) {
                array_push($scorecards, array(
                    'id' => $scorecard->id,
                    'name' => $ballot_item->cards[$i]->name,
                    'description' => $ballot_item->cards[$i]->description,
                    'vote' => $scorecard->vote->name,
                    'vote_icon' => $scorecard->vote->icon,
                ));
                ++$i;
            }

            $wrapped_ballot_item['scorecards'] = $scorecards;
        }

        if (in_array('news', $includes)) {
            $wrapped_ballot_item['news'] = $ballot_item->ballotItemNews;
        }

        return $wrapped_ballot_item;
    }

    /**
     * return an array of wrapped ballots
     * @param $ballots array of BallotItem Objects
     * @return array array of wrapped ballots
     */
    private function ballotItemsWrapper(array $ballots, $includes = array()) {
        $wrapped_ballots = array();
        foreach ($ballots as $ballot)
            array_push($wrapped_ballots, $this->ballotItemWrapper($ballot, $includes));

        return $wrapped_ballots;
    }

}

?>
