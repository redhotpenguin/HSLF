<?php

class BallotItemsAPI extends APIBase implements IAPI {

    public function getList($arguments = array()) {
        /*
         * arguments could be: order_by, endorser=1
         */
        $criteria = array();

        if (isset($arguments['endorser'])) {
            $filters['endorserId'] = $arguments['endorserId'];
        }

        if (isset($arguments['state'])) {
            $filters['stateAbbr'] = $arguments['state'];
        }

        if (empty($filters))
            $result = $this->getOverview();
        else {
            $result = $this->getByCriteria($filters);
        }


        return $result;
    }

    public function getSingle($id) {
        $ballot_item = BallotItem::model()->findByPk($id);
        if ($ballot_item != false)
            $result = $this->ballotItemWrapper($ballot_item);
        else
            $result = false;

        return $result;
    }

    private function getByCriteria(array $filters) {
        $ballotItem = new BallotItem;
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'district',
            'recommendation',
            'electionResult',
            'ballotItemNews',
            'scorecards',
            'cards',
            'office',
            'party',
            'endorsers',
        );


        // query only published items
        $criteria->addCondition("published='yes'", 'AND');

        if (isset($filters['stateAbbr']))
            $criteria->compare('district.state_abbr', $filters['stateAbbr'], true);



        $activeDataProvider = new CActiveDataProvider($ballotItem, array(
                    'criteria' => $criteria,
                ));


        $ballotItems = $activeDataProvider->getData();
        if ($ballotItems)
            $result = $this->ballotItemsWrapper($ballotItems);
        else
            $result = "";
        
        return $result;
    }

    /**
     * return a limited overview of the ballot items
     * @param string $measure_order = "ASC" order by measure
     * @return ballot return array of ballot item object
     */
    private function getOverview($measure_order = "ASC") {
        $ballot_items = Yii::app()->db->createCommand()
                ->select('b.id, item, b.measure_number, item_type, d.type AS district_type, d.state_abbr, d.number AS district_number, d.display_name AS district_display_name, r.type AS recommendation_type, measure_number, friendly_name')
                ->from(array('ballot_item b'))
                ->join('district d', 'b.district_id=d.id')
                ->join('recommendation r', 'b.recommendation_id=r.id')
                ->where('published=:published AND next_election_date>=:current_date or next_election_date ISNULL', array(
                    ':published' => 'yes',
                    ':current_date' => date('Y-m-d'), // use NOW() instead?
                ))
                ->order("d.state_abbr ASC, b.measure_number {$measure_order}")
                ->queryAll();

        return $ballot_items;
    }

    /**
     * return all ballot items that have a specificied endorser
     * @param integer $endorser_id id of the endorser
     * @return ballot return array of ballot item object
     */
    private function getByEndorser($endorser_id) {
        $ballot_items = BallotItemManager::findByEndorser($endorser_id);
        if (!empty($ballot_items)) {
            return $this->ballotItemsWrapper($ballot_items);
        }

        else
            $this->_sendResponse(404, "no_ballot_found");
    }

    /**
     * return a wrapped ballot array
     * @param $ballot BallotItem ballot item
     * @return array wrapped ballot
     */
    private function ballotItemWrapper(BallotItem $ballot_item) {
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

        $wrapped_ballot_item = array(
            'id' => $ballot_item->id,
            'item' => $ballot_item->item,
            'item_type' => $ballot_item->item_type,
            'recommendation' => $ballot_item->recommendation,
            'next_election_date' => $ballot_item->next_election_date,
            'priority' => $ballot_item->priority,
            'detail' => $ballot_item->detail,
            'date_published' => $ballot_item->date_published,
            'party' => $ballot_item->party,
            'image_url' => $ballot_item->image_url,
            'electionResult' => $ballot_item->electionResult,
            'url' => $ballot_item->url,
            'personal_url' => $ballot_item->personal_url,
            'score' => $ballot_item->score,
            'office_type' => $ballot_item->office->name,
            'district' => $ballot_item->district,
            'Scorecard' => $scorecards,
            'BallotItemNews' => $ballot_item->ballotItemNews,
            'facebook_url' => $ballot_item->facebook_url,
            'facebook_share' => $ballot_item->facebook_share,
            'twitter_handle' => $ballot_item->twitter_handle,
            'twitter_share' => $ballot_item->twitter_share,
            'hold_office' => $ballot_item->hold_office,
            'endorsers' => $ballot_item->endorsers,
            'measure_number' => $ballot_item->measure_number,
            'friendly_name' => $ballot_item->friendly_name
        );

        return $wrapped_ballot_item;
    }

    /**
     * return an array of wrapped ballots
     * @param $ballots array of BallotItem Objects
     * @return array array of wrapped ballots
     */
    private function ballotItemsWrapper(array $ballots) {
        $wrapped_ballots = array();
        foreach ($ballots as $ballot)
            array_push($wrapped_ballots, $this->ballotItemWrapper($ballot));

        return $wrapped_ballots;
    }

}

?>
