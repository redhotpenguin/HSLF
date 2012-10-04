<?php

class BallotItemEndorserBehavior extends CBehavior {

    /**
     * Get array of endorsers a ballot item has
     * @return array of endorsers
     */
    public function getEndorsers() {

        $endorsers = Endorser::model()->with('ballot_items')->find(
                array(
                    'condition' => 'ballot_item_id = :ballot_item_id',
                    'params' => array(':ballot_item_id' => $this->owner->id)
                ));


        return $endorsers;
    }

    /**
     * Get array of ballot items by endorser id
     * @param integer $endorser_id
     * @return array of ballot items
     */
    public function findByEndorser($endorser_id) {
        $ballot_items = BallotItem::model()->with('ballotItemEndorsers')->findAll(
                array(
                    'condition' => "endorser_id = :endorser_id AND published =:published",
                    'params' => array(
                        ':endorser_id' => $endorser_id,
                        ':published' => 'yes',
                    )
                ));


        return $ballot_items;
    }

    /**
     * Get array of ballot items by endorser id
     * @param integer $endorser_id
     * @return array of ballot items
     */
    public function findByEndorserWithPosition($endorser_id) {
        $ballot_items = BallotItem::model()->with('ballotItemEndorsers')->findAll(
                array(
                    'condition' => "endorser_id = :endorser_id AND published =:published AND  position !=:position   ",
                    'params' => array(
                        ':endorser_id' => $endorser_id,
                        ':published' => 'yes',
                        ':position' => 'np'
                    )
                ));


        return $ballot_items;
    }

    /**
     * Verifiy if a ballot item has a specific endorser
     * @param integer $endorser_id
     * @return boolean
     */
    public function hasEndorser($endorser_id) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();


        $result = $command->select('id')
                ->from('endorser_ballot_item')
                ->where('endorser_id=:endorser_id AND ballot_item_id=:ballot_item_id', array(':endorser_id' => $endorser_id, ':ballot_item_id' => $this->owner->id))
                ->queryRow();

        if (isset($result['id']))
            return true;

        return false;
    }

    /**
     * Add an endorser to a ballot item
     * @param integer $endorser_id
     * @param string $position
     * @return boolean
     */
    public function addEndorser($endorser_id, $position = 'np') {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $add_endorser_result = "";

        // if the ballot item is already endorsed, return false;
        if ($this->hasEndorser($endorser_id)) {
            return $this->updateEndorserPosition($endorser_id, $position);
        }

        $ballotItemEndorser = new BallotItemEndorser();
        $ballotItemEndorser->ballot_item_id = $this->owner->id;
        $ballotItemEndorser->endorser_id = $endorser_id;
        $ballotItemEndorser->position = $position;
        $ballotItemEndorser->save();

        return (boolean) $add_endorser_result;
    }

    /**
     * Update the position of a ballot item endorser
     * @param integer $endorser_id
     * @param string $position
     * @return boolean
     */
    public function updateEndorserPosition($endorser_id, $position = 'np') {
        $ballotItemEndorser = BallotItemEndorser::model()->findByAttributes(array(
            'endorser_id' => $endorser_id,
            'ballot_item_id' => $this->owner->id
                ));

        if ($ballotItemEndorser) {
            $ballotItemEndorser->position = $position;
            $ballotItemEndorser->save();
        }else
            return false;
    }

    /**
     * Remove all endorsers for a ballot item
     * return boolean true or false
     */
    public function removeEndorsers() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_endorsers_result = $command->delete(
                'endorser_ballot_item', 'ballot_item_id=:ballot_item_id', array(':ballot_item_id' => $this->owner->id)
        );


        return $delete_endorsers_result;
    }

    /**
     * Remove all endorsers for a ballot item not present in the argument
     * @param array endorser IDs to keep
     * return boolean true or false
     */
    public function removeEndorsersNotIn(array $endorser_ids) {

        if (empty($endorser_ids)) {
            return false;
        }

        $endorser_ids_str = implode(',', $endorser_ids);

        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_endorsers_result = $command->delete(
                'endorser_ballot_item', "ballot_item_id =:ballot_item_id  AND endorser_id NOT IN( {$endorser_ids_str} )", array(
            ':ballot_item_id' => $this->owner->id,
                )
        );

        return $delete_endorsers_result;
    }

}

?>
