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


        return $result;
    }

    /**
     * Add an endorser to a ballot item
     * @param integer $endorser_id
     * @return boolean
     */
    public function addEndorser($endorser_id) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);

        // if the ballot item is already endorsed, return false;
        if ($this->findEndorser($endorser_id))
            return false;

        try {
            $add_endorser_result = $command->insert('endorser_ballot_item', array(
                'ballot_item_id' => $this->owner->id,
                'endorser_id' => $endorser_id
                    ));
        } catch (CException $ce) {
            error_log("Could not add endorser to ballot item {$this->owner->id}: " . $ce->getMessage());
            $add_tag_result = false;
        }


        return (boolean) $add_endorser_result;
    }

    /**
     * Check if a ballot item has an endorser
     * @param integer $endorser_id endorser id
     * @return endorser id for success. False for failure.
     */
    public function findEndorser($endorser_id) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $result = $command->select('endorser_id')
                ->from('endorser_ballot_item')
                ->where('endorser_id=:endorser_id AND ballot_item_id=:ballot_item_id', array(':endorser_id' => $endorser_id, ':ballot_item_id' => $this->owner->id))
                ->queryRow();

        return $result['endorser_id'];
    }

    /**
     * Remove all endorsers for a ballot item
     * return boolean true or false
     */
    public function removeEndorsers() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_endorsers_result = $command->delete(
                'endorser_ballot_item', 
                'ballot_item_id=:ballot_item_id', 
                 array(':ballot_item_id' => $this->owner->id)
        );
        
        
        return $delete_endorsers_result;
    }

}

?>
