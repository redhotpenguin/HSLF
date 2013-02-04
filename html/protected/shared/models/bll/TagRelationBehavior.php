<?php

class TagrelationBehavior extends CActiveRecordBehavior {

    public $joinTableName; // join table name that links tag to the owner
    public $tagRelationName; // relation to the owner, defined in Tag.relations()
    public $foreignKeyName; // foreign key name joining the join-table to the owner

    /**
     * link a tag to the owner
     * @param integer tag id 
     * @return boolean true on success or if tag is already linked, false on failure
     */

    public function addTagAssociation($tagId) {

        // check that tag actually exists and also check tenancy (through MultiTenantBehavior)
        $tag = Tag::model()->findByPk($tagId);
        if (!$tag)
            return false;

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $data = array(
            'tag_id' => $tagId,
            $this->foreignKeyName => $this->owner->id
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
     * remove a tag associated to the owner
     * @param integer tag id 
     * @return boolean true on success or false on failure
     */
    public function removeTagAssociation($tagId) {
        // check that tag actually exists and also check tenancy (through MultiTenantBehavior)
        $tag = Tag::model()->findByPk($tagId);
        if (!$tag)
            return false;

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $condition = "tag_id =:tag_id AND {$this->foreignKeyName} =:{$this->foreignKeyName}";

        $params = array(
            ":{$this->foreignKeyName}" => $this->owner->id,
            ":tag_id" => $tagId
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
     * remove all tags associated to the the owner
     * @return boolean true on success or false on failure
     */
    public function removeAllTagsAssociation() {

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
     * Return all the tags linked to the owner
     * @param array - Array of Tags
     */
    public function getTags() {

        $tags = Tag::model()->with($this->tagRelationName)->findAll(
                array(
                    'condition' => "{$this->foreignKeyName} =:{$this->foreignKeyName}",
                    'params' => array(":{$this->foreignKeyName}" => $this->owner->id)
                ));

        return $tags;
    }

    /**
     * Return whether the owner has a tag associated to it or not
     * @param integer $tagId
     * @return boolean
     */
    public function hasTag($tagId) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $result = $command->select('*')
                ->from($this->joinTableName)
                ->where("tag_id =:tag_id AND {$this->foreignKeyName} =:{$this->foreignKeyName}", array(":{$this->foreignKeyName}" => $this->owner->id,
                    ":tag_id" => $tagId))
                ->queryRow();

        if (isset($result['tag_id']) && isset($result[$this->foreignKeyName]))
            return true;

        return false;
    }

    public function massUpdateTags(array $tagIds) {
        $this->removeAllTagsAssociation();
        foreach ($tagIds as $tagId)
            $this->addTagAssociation($tagId);
    }

}