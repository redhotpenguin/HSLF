<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

/**
 * This is the model class for table "app_user".
 *
 * The followings are the available columns in table 'app_users':
 * @property integer $id
 * @property string $device_token
 * @property string $latitude
 * @property string $longitude
 * @property integer $district_id
 * @property string $registration
 * @property string $type
 * @property string $user_agent
 * @property string $uap_user_id
 *
 */
class Application_user extends CActiveRecord {

    public $state_abbr; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Application_user the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'app_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('device_token, district_id, type, registration', 'required'),
            array('district_id', 'numerical', 'integerOnly' => true),
            array('device_token', 'length', 'max' => 128),
            array('user_agent', 'length', 'max' => 1024),
            array('latitude, longitude, registration, type, tags', 'safe'),
            array('registration', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('id, device_token, latitude, longitude, state_abbr, district_id, district_type, district_number, registration, type, user_agent', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'district' => array(self::BELONGS_TO, 'District', 'district_id'),
            'tags' => array(self::MANY_MANY, 'Tag', 'app_user_tag(app_user_id, tag_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'device_token' => 'Device Token',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'district_id' => 'District',
            'registration' => 'Registration',
            'type' => 'Device Type',
            'user_agent' => 'User Agent',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->with = array('district');


        // search by relationship (district)
        if ($this->district_number || $this->district_type || $this->state_abbr) {
            $criteria->together = true;
            // Join the 'district' table

            if ($this->district_number)
                $criteria->compare('district.number', $this->district_number, false);
            if ($this->district_type)
                $criteria->compare('district.type', $this->district_type, true);
            if ($this->state_abbr)
                $criteria->compare('district.state_abbr', $this->state_abbr, true);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('device_token', $this->device_token, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('registration', $this->registration, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('user_agent', $this->user_agent, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                    'sort' => array(
                        'attributes' => array(
                            'state_abbr' => array(
                                'asc' => 'district.state_abbr',
                                'desc' => 'district.state_abbr DESC',
                            ),
                            'district_type' => array(
                                'asc' => 'district.type',
                                'desc' => 'district.type DESC',
                            ),
                            'district_number' => array(
                                'asc' => 'district.number',
                                'desc' => 'district.number DESC',
                            ),
                            '*',
                    ))
                ));
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->registration = date('Y-m-d H:i:s');

            if (isset($_SERVER['HTTP_USER_AGENT']))
                $this->user_agent = $_SERVER['HTTP_USER_AGENT']; //should really be in the controller\
            else
                $this->user_agent = 'UNAVALAIBLE';
        }

        if (!$this->latitude)
            $this->latitude = NULL;
        if (!$this->longitude)
            $this->longitude = NULL;

        // synchronize tags with urban airship
        $this->synchronizeUAPTags();

        return parent::beforeSave();
    }

    /**
     * Find  user meta
     * @param string $meta_key meta key
     * @param boolean $unique if true, only returns one record, if false, returns all match
     * $param integer $app_user_id application user id
     * @return Meta value or false.
     */
    public function getMeta($meta_key, $unique = false, $app_user_id = null) {
        if (empty($app_user_id))
            $app_user_id = $this->id;

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
            $app_user_id = $this->id;

        $meta_query = Yii::app()->db->createCommand()
                ->select('id, meta_key, meta_value')
                ->from('app_user_meta')
                ->where('app_user_id=:app_user_id', array(':app_user_id' => $app_user_id));

        return $meta_query->queryAll();
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
            $app_user_id = $this->id;


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
            $app_user_id = $this->id;

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
            $app_user_id = $this->id;
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
                'app_user_id' => $this->id,
                'tag_id' => $tag_id
                    ));
        } catch (CException $ce) {
            error_log("Could not add tag to app user_id $this->id" . $ce->getMessage());
            $add_tag_result = false;
        }

        return $add_tag_result;
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

        $delete_tag_result = $command->delete('app_user_tag', 'app_user_id=:app_user_id AND tag_id=:tag_id', array(':app_user_id' => $this->id, ':tag_id' => $tag_id));

        return $delete_tag_result;
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
                ->where('app_user_id=:app_user_id AND tag_id=:tag_id', array(':app_user_id' => $this->id, ':tag_id' => $tag_id))
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
                ->where('app_user_id=:app_user_id AND tag.id=app_user_tag.tag_id', array(':app_user_id' => $this->id))
                ->queryAll();



        // return $result;
        return array_map(array(&$this, 'extract_first_el'), $result);
    }

    private function extract_first_el($a) {
        return $a['name'];
    }

    public function synchronizeUAPTags() {

        $uap_notifier = new UrbanAirshipNotifier();

        $uap_tags = array(
            $this->district->state_abbr,
            $this->district->state_abbr . '_' . $this->district->type . '_' . $this->district->number,
        );

        foreach ($this->getTagsName() as $tag)
            array_push($uap_tags, $tag);

        return $uap_notifier->updateRichUserTags($this->uap_user_id, $this->device_token, $uap_tags);
    }


}