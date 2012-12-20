<?php

class ItemOrganizationBehavior extends CBehavior {

    /**
     * Get array of organizations an item has
     * @return array of organizations
     */
    public function getOrganizations() {

        $organizations = Organization::model()->with('items')->find(
                array(
                    'condition' => 'item_id = :item_id',
                    'params' => array(':item_id' => $this->owner->id)
                ));


        return $organizations;
    }

    /**
     * Get array of items by organization id
     * @param integer $organization_id
     * @return array of items
     */
    public function findByOrganization($organization_id) {
        $items = Item::model()->with('itemOrganizations')->findAll(
                array(
                    'condition' => "organization_id = :organization_id AND published =:published",
                    'params' => array(
                        ':organization_id' => $organization_id,
                        ':published' => 'yes',
                    )
                ));


        return $items;
    }

    /**
     * Get array of items by organization id
     * @param integer $organization_id
     * @return array of items
     */
    public function findByOrganizationWithPosition($organization_id) {
        $items = Item::model()->with('itemOrganizations')->findAll(
                array(
                    'condition' => "organization_id = :organization_id AND published =:published AND  position !=:position   ",
                    'params' => array(
                        ':organization_id' => $organization_id,
                        ':published' => 'yes',
                        ':position' => 'np'
                    )
                ));


        return $items;
    }

    /**
     * Verifiy if a item has a specific organization
     * @param integer $organization_id
     * @return boolean
     */
    public function hasOrganization($organization_id) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();


        $result = $command->select('id')
                ->from('organization_item')
                ->where('organization_id=:organization_id AND item_id=:item_id', array(':organization_id' => $organization_id, ':item_id' => $this->owner->id))
                ->queryRow();

        if (isset($result['id']))
            return true;

        return false;
    }

    /**
     * Add an organization to an item
     * @param integer $organization_id
     * @param string $position
     * @return boolean
     */
    public function addOrganization($organization_id, $position = 'np') {
        $connection = Yii::app()->db;
        $add_organization_result = "";

        if ($this->hasOrganization($organization_id)) {
            return $this->updateOrganizationPosition($organization_id, $position);
        }

        $itemOrganization = new ItemOrganization();
        $itemOrganization->item_id = $this->owner->id;
        $itemOrganization->organization_id = $organization_id;
        $itemOrganization->position = $position;
        $itemOrganization->save();

        return (boolean) $add_organization_result;
    }

    /**
     * Update the position of an item organization
     * @param integer $organization_id
     * @param string $position
     * @return boolean
     */
    public function updateOrganizationPosition($organization_id, $position = 'np') {
        $itemOrganization = ItemOrganization::model()->findByAttributes(array(
            'organization_id' => $organization_id,
            'item_id' => $this->owner->id
                ));

        if ($itemOrganization) {
            $itemOrganization->position = $position;
            $itemOrganization->save();
        }else
            return false;
    }

    /**
     * Remove an organizations for an item
     * @param integer $organizationId
     * return boolean true or false
     */
    public function removeOrganization($organizationId) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_organizations_result = $command->delete(
                'organization_item', 'item_id=:item_id AND organization_id=:organization_id',
                array(
                    ':item_id' => $this->owner->id,
                    ':organization_id' => $organizationId
                    
                )
        );


        return $delete_organizations_result;
    }

    /**
     * Remove all organizations for an item
     * return boolean true or false
     */
    public function removeOrganizations() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_organizations_result = $command->delete(
                'organization_item', 'item_id=:item_id', array(':item_id' => $this->owner->id)
        );


        return $delete_organizations_result;
    }

    /**
     * Remove all organizations for an item not present in the argument
     * @param array organization IDs to keep
     * return boolean true or false
     */
    public function removeOrganizationsNotIn(array $organization_ids) {

        if (empty($organization_ids)) {
            return false;
        }

        $organization_ids_str = implode(',', $organization_ids);

        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        $delete_organizations_result = $command->delete(
                'organization_item', "item_id =:item_id  AND organization_id NOT IN( {$organization_ids_str} )", array(
            ':item_id' => $this->owner->id,
                )
        );

        return $delete_organizations_result;
    }

}

?>
