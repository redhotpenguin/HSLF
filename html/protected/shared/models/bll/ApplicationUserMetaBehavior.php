<?php

/**
 * Behavior class for the Application user Model
 *
 * @author jonas
 */
class ApplicationUserMetaBehavior extends CBehavior {

    /**
     * Find  user meta
     * @param string $meta_key meta key
     * @param boolean $unique if true, only returns one record, if false, returns all match
     * $param integer $app_user_id application user id
     * @return Meta value or false.
     */
    public function getMeta($meta_key, $unique = false, $app_user_id = null) {
        if (empty($app_user_id))
            $app_user_id = $this->owner->id;

        $meta_query = Yii::app()->db->createCommand()
                ->select('id, meta_key, meta_value')
                ->from('app_user_meta')
                ->where('app_user_id=:app_user_id AND meta_key=:meta_key', array(':app_user_id' => $app_user_id, ':meta_key' => $meta_key));

        if ($unique == true) {
            $result = $meta_query->queryRow();
        } else {
            $result = $meta_query->queryAll();
        }

        if (empty($result))
            return false;
        else
            return $result;
    }

    /**
     * Get all the meta data associated to an app user
     * $param integer $app_user_id application user id
     * @return array of metas
     */
    public function getAllMeta($app_user_id = null) {

        if (empty($app_user_id))
            $app_user_id = $this->owner->id;

        $meta_query = Yii::app()->db->createCommand()
                ->select('id, meta_key, meta_value')
                ->from('app_user_meta')
                ->where('app_user_id=:app_user_id', array(':app_user_id' => $app_user_id));

        return $meta_query->queryAll();
    }

    /**
     * Update an app user meta data
     * @param string $meta_key meta key
     * @param string $meta_value meta meta_value
     * @param string $existing_meta_value  existing_meta_value
     * $param integer $app_user_id application user id
     * @return true for success. False for failure.
     */
    public function updateMeta($meta_key, $meta_value, $existing_meta_value = null, $app_user_id = null) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        if (empty($app_user_id))
            $app_user_id = $this->owner->id;

        // meta key doesn't exist, create a new one.
        if (!$this->getMeta($meta_key, true)) {
            return $this->addMeta($meta_key, $meta_value);
        }

        if (isset($existing_meta_value)) {
            $meta_update_result = $command->update('app_user_meta', array(
                'meta_value' => $meta_value,
                    ), 'app_user_id=:app_user_id AND meta_key=:meta_key AND meta_value=:old_meta_value', array(
                ':app_user_id' => $app_user_id,
                ':meta_key' => $meta_key,
                ':old_meta_value' => $existing_meta_value,
                    ));
        } else {
            $meta_update_result = $command->update('app_user_meta', array(
                'meta_value' => $meta_value,
                    ), 'app_user_id=:app_user_id AND meta_key=:meta_key', array(
                ':app_user_id' => $app_user_id,
                ':meta_key' => $meta_key,
                    ));
        }



        if ($meta_update_result > 0) {
            return true;
        }
        else
            return false;
    }

    /**
     * Add  an app user meta data
     * @param string $meta_key meta key
     * @param string $meta_value meta meta_value
     * $param integer $app_user_id application user id
     * @return true for success. False for failure.
     */
    public function addMeta($meta_key, $meta_value, $app_user_id = null) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        if (empty($app_user_id))
            $app_user_id = $this->owner->id;


        $meta_add_result = $command->insert('app_user_meta', array(
            'app_user_id' => $app_user_id,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
                ));


        if ($meta_add_result > 0) {
            return true;
        }
        else
            return false;
    }

    /**
     * Delete an appuser meta data
     * @param string $meta_key meta key
     * @param string $meta_value meta meta_value
     * $param integer $app_user_id application user id
     * @return true for success. False for failure.
     */
    public function deleteMeta($meta_key, $meta_value = null, $app_user_id = null) {
        $connection = Yii::app()->db;
        $command = $connection->createCommand();

        if (empty($app_user_id)) {
            $app_user_id = $this->owner->id;
        }

        if (isset($meta_value)) {

            $meta_delete_result = $command->delete('app_user_meta', 'app_user_id=:app_user_id AND meta_key=:meta_key AND meta_value=:meta_value', array(':app_user_id' => $app_user_id, ':meta_key' => $meta_key, ':meta_value' => $meta_value));
        } else {

            $meta_delete_result = $command->delete('app_user_meta', 'app_user_id=:app_user_id AND meta_key=:meta_key', array(':app_user_id' => $app_user_id, ':meta_key' => $meta_key));
        }


        if ($meta_delete_result > 0) {
            return true;
        }
        else
            return false;
    }

    /**
     * Update multiple multiple metas
     * @param array of meta data (key/value)
     * @return true for success. False for failure.
     */
    public function updateMassMeta(array $metas) {
        $result = true;
        foreach ($metas as $meta_key => $meta_value) {
           $update_result =  $this->updateMeta($meta_key, $meta_value);
           if($update_result == false)
               $result = false;
        }
        return $result;
    }

}

?>
