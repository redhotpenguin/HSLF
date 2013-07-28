<?php

class ContactRelationBehavior extends CActiveRecordBehavior {

    public $joinTableName; // join table name that links contact to the owner
    public $relationName; // relation to the owner, defined in Contact.relations()
    public $foreignKeyName; // foreign key name joining the join-table to the owner

    /**
     * link a contact to the owner
     * @param integer contact id 
     * @return boolean true on success or if contact is already linked, false on failure
     */

    public function addContactAssociation($contactId, $position = 0) {

        // check that contact actually exists and also check tenancy (through MultiTenantBehavior)
        $contact = Contact::model()->findByPk($contactId);
        if (!$contact)
            return false;

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $data = array(
            'contact_id' => $contactId,
            $this->foreignKeyName => $this->owner->id,
            'position' => $position
         );

        try {
            $command->insert($this->joinTableName, $data);
            $result = true;
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) // duplication
                $result = true;

            else
                $result = false;
        }

        return $result;
    }

    /**
     * remove a contact associated to the owner
     * @param integer contact id 
     * @return boolean true on success or false on failure
     */
    public function removeContactAssociation($contactId) {
        // check that contact actually exists and also check tenancy (through MultiTenantBehavior)
        $contact = Contact::model()->findByPk($contactId);
        if (!$contact)
            return false;

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $condition = "contact_id =:contact_id AND {$this->foreignKeyName} =:{$this->foreignKeyName}";

        $params = array(
            ":{$this->foreignKeyName}" => $this->owner->id,
            ":contact_id" => $contactId
        );

        try {
            $command->delete($this->joinTableName, $condition, $params);
            $result = true;
        } catch (CDbException $e) {
            $result = false;
        }

        return $result;
    }

    /**
     * remove all contacts associated to the the owner
     * @return boolean true on success or false on failure
     */
    public function removeAllContactsAssociation() {

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $condition = "{$this->foreignKeyName} =:{$this->foreignKeyName}";

        $params = array(
            ":{$this->foreignKeyName}" => $this->owner->id,
        );

        try {
            $command->delete($this->joinTableName, $condition, $params);
            $result = true;
        } catch (CDbException $e) {
            $result = false;
        }
        

        return $result;
    }

    /**
     * Return all the contacts linked to the owner
     * @param array - Array of Contacts
     */
    public function getContacts() {

        $contacts = Contact::model()->with($this->relationName)->findAll(
                array(
                    'condition' => "{$this->foreignKeyName} =:{$this->foreignKeyName}",
                    'params' => array(":{$this->foreignKeyName}" => $this->owner->id),
                    'order' => 'position ASC'
                ));
                    
        return $contacts;
    }

    /**
     * Return whether the owner has a contact associated to it or not
     * @param integer $contactId
     * @return boolean
     */
    public function hasContact($contactId) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $result = $command->select('*')
                ->from($this->joinTableName)
                ->where("contact_id =:contact_id AND {$this->foreignKeyName} =:{$this->foreignKeyName}", array(":{$this->foreignKeyName}" => $this->owner->id,
                    ":contact_id" => $contactId))
                ->queryRow();

        if (isset($result['contact_id']) && isset($result[$this->foreignKeyName]))
            return true;

        return false;
    }

    public function massUpdateContacts(array $contactIds) {
        $this->removeAllContactsAssociation();
        foreach ($contactIds as $position => $contactId)
            $this->addContactAssociation($contactId, $position);
    }

}