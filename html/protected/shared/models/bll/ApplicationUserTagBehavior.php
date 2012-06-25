<?php

/**
 * Tag Behavior class for the Application user Model
 *
 * @author jonas
 */
class ApplicationUserTagBehavior extends CBehavior {

    /**
     * Add a tag to an app_user, uses app_user_tag as the joining table
     * @param integer $tag id of the tag or string $tag name of the tag
     * @return true for success. False for failure.
     */
    public function addTag($tag) {
        if (is_numeric($tag))
            $tag_id = $tag;
        elseif (is_string($tag))
            $tag_id = Tag::model()->getTagId($tag);
        else
            return false;


        // if the app user already has a tag, returns false;
        if ($this->findTag($tag_id))
            return false;

        $connection = Yii::app()->db;

        $command = $connection->createCommand($sql);

        try {
            $add_tag_result = $command->insert('app_user_tag', array(
                'app_user_id' => $this->owner->id,
                'tag_id' => $tag_id
                    ));
        } catch (CException $ce) {
            error_log("Could not add tag to app user_id {$this->owner->id}: " . $ce->getMessage());
            $add_tag_result = false;
        }


        return (boolean) $add_tag_result;
    }

    public function deleteTag($tag) {
        if (is_numeric($tag))
            $tag_id = $tag;
        elseif (is_string($tag))
            $tag_id = Tag::model()->getTagId($tag);
        else
            return false;

        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $delete_tag_result = $command->delete('app_user_tag', 'app_user_id=:app_user_id AND tag_id=:tag_id', array(':app_user_id' => $this->owner->id, ':tag_id' => $tag_id));

        return (boolean) $delete_tag_result;
    }

    /**
     * Check if an application has the specified tag
     * @param integer $tag id of the tag or string $tag name of the tag
     * @return tag id for success. False for failure.
     */
    public function findTag($tag) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();
        if (is_numeric($tag))
            $tag_id = $tag;
        else
            $tag_id = Tag::model()->getTagId($tag);


        $result = $command->select('tag_id')
                ->from('app_user_tag')
                ->where('app_user_id=:app_user_id AND tag_id=:tag_id', array(':app_user_id' => $this->owner->id, ':tag_id' => $tag_id))
                ->queryRow();

        return $result['tag_id'];
    }

    /**
     * Return all the tags associated to a user
     * @return tag array object
     */
    public function getTagsName() {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        $result = $command->select('name')
                ->from(array('app_user_tag', 'tag'))
                ->where('app_user_id=:app_user_id AND tag.id=app_user_tag.tag_id', array(':app_user_id' => $this->owner->id))
                ->queryAll();



        // return $result;
        return array_map(array(&$this, 'extract_first_el'), $result);
    }

    
    private function extract_first_el($a) {
        return $a['name'];
    }

}

?>
