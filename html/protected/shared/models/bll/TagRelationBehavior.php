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

    public function linkTag($tagId) {
        
        // check that tag actually exists and also check tenancy (through MultiTenantBehavior)
        $tag = Tag::model()->findByPk($tagId);
        if(!$tag)
            return false;
      
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $data = array(
            'tag_id' => $tagId,
            $this->foreignKeyName => $this->owner->id
        );

        try {
            $result = $cmdResult = $command->insert($this->joinTableName, $data);
        } catch (CDbException $e) {
            if ($e->getCode() == 23505) // duplication
                $result = true;

            else
                $result = false;
        }

        return $result;
    }

    /**
     * Return all the tags linked to the owner
     * @param array - Array of Tags
     */
    public function getTags() {

        $tags = Tag::model()->with($this->tagRelationName)->find(
                array(
                    'condition' => "{$this->foreignKeyName} = :{$this->foreignKeyName}",
                    'params' => array(":{$this->foreignKeyName}" => $this->owner->id)
                ));


        return $tags;
    }

}