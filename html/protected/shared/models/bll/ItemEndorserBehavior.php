<?php

class ItemEndorserBehavior extends CBehavior {

    /**
     * Get array of endorsers an item has
     * @return array of endorsers
     */
    public function getEndorsers() {

        $endorsers = Endorser::model()->with('items')->find(
                array(
                    'condition' => 'item_id = :item_id',
                    'params' => array(':item_id' => $this->owner->id)
                ));


        return $endorsers;
    }

    /**
     * Get array of items by endorser id
     * @param integer $endorser_id
     * @return array of items
     */
    public function findByEndorser($endorser_id) {
        $items = Item::model()->with('itemEndorsers')->findAll(
                array(
                    'condition' => "endorser_id = :endorser_id AND published =:published",
                    'params' => array(
                        ':endorser_id' => $endorser_id,
                        ':published' => 'yes',
                    )
                ));


        return $items;
    }

    /**
     * Get array of items by endorser id
     * @param integer $endorser_id
     * @return array of items
     */
    public function findByEndorserWithPosition($endorser_id) {
        $items = Item::model()->with('itemEndorsers')->findAll(
                array(
                    'condition' => "endorser_id = :endorser_id AND published =:published AND  position !=:position   ",
                    'params' => array(
                        ':endorser_id' => $endorser_id,
                        ':published' => 'yes',
                        ':position' => 'np'
                    )
                ));


        return $items;
    }

    /**
     * Verifiy if a item has a specific endorser
     * @param integer $endorser_id
     * @return boolean
     */
    public function hasEndorser($endorser_id) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();


        $result = $command->select('id')
                ->from('endorser_item')
                ->where('endorser_id=:endorser_id AND item_id=:item_id', array(':endorser_id' => $endorser_id, ':item_id' => $this->owner->id))
                ->queryRow();

        if (isset($result['id']))
            return true;

        return false;
    }

    /**
     * Add an endorser to an item
     * @param integer $endorser_id
     * @param string $position
     * @return boolean
     */
    public function addEndorser($endorser_id, $position = 'np') {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $add_endorser_result = "";

        // if the  item is already endorsed, return false;
        if ($this->hasEndorser($endorser_id)) {
            return $this->updateEndorserPosition($endorser_id, $position);
        }

        $itemEndorser = new ItemEndorser();
        $itemEndorser->item_id = $this->owner->id;
        $itemEndorser->endorser_id = $endorser_id;
        $itemEndorser->position = $position;
        $itemEndorser->save();

        return (boolean) $add_endorser_result;
    }

    /**
     * Update the position of an item endorser
     * @param integer $endorser_id
     * @param string $position
     * @return boolean
     */
    public function updateEndorserPosition($endorser_id, $position = 'np') {
        $itemEndorser = ItemEndorser::model()->findByAttributes(array(
            'endorser_id' => $endorser_id,
            'item_id' => $this->owner->id
                ));

        if ($itemEndorser) {
            $itemEndorser->position = $position;
            $itemEndorser->save();
        }else
            return false;
    }

    /**
     * Remove all endorsers for an item
     * return boolean true or false
     */
    public function removeEndorsers() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_endorsers_result = $command->delete(
                'endorser_item', 'item_id=:item_id', array(':item_id' => $this->owner->id)
        );


        return $delete_endorsers_result;
    }

    /**
     * Remove all endorsers for an item not present in the argument
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
                'endorser_item', "item_id =:item_id  AND endorser_id NOT IN( {$endorser_ids_str} )", array(
            ':item_id' => $this->owner->id,
                )
        );

        return $delete_endorsers_result;
    }

}

?>
