<?php

Yii::import('application.vendors.*');
require_once('urbanairship/urbanairship.php');

/**
 * This is the model class for table "app_users".
 *
 * The followings are the available columns in table 'app_users':
 * @property integer $id
 * @property string $device_token
 * @property string $latitude
 * @property string $longitude
 * @property string $state_abbr
 * @property integer $district_id
 * @property string $registration
 * @property string $type
 * @property string $user_agent
 *
 */
class Application_users extends CActiveRecord {

    public $district_number; // not part of the model, here for cgridview

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Application_users the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'app_users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('device_token, state_abbr, district_id, type', 'required'),
            array('district_id', 'numerical', 'integerOnly' => true),
            array('device_token', 'length', 'max' => 128),
            array('state_abbr', 'length', 'max' => 3),
            array('user_agent', 'length', 'max' => 1024),
            array('latitude, longitude, registration, type', 'safe'),
            array('id, device_token, latitude, longitude, state_abbr, district_number, registration, type, user_agent', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'stateAbbr' => array(self::BELONGS_TO, 'State', 'state_abbr'),
            'district' => array(self::BELONGS_TO, 'District', 'district_id'),
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
            'state_abbr' => 'State Abbr',
            'district_id' => 'District ID',
            'registration' => 'Registration',
            'type' => 'Type',
            'user_agent' => 'User Agent',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        
        if ($this->district_number) {
            $criteria->together = true;
            // Join the 'district' table
            $criteria->with = array('district');
     
            $criteria->compare('district.number', $this->district_number, false);
            $criteria->compare('district.state_abbr', $this->state_abbr, false);
        } else {
            $criteria->together = false;
            $criteria->with = array();
            $criteria->compare('state_abbr', $this->state_abbr, true);
 
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('device_token', $this->device_token, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('registration', $this->registration, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('user_agent', $this->user_agent, true);




        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria
                ));
    }

    public function beforeSave() {

        if ($this->isNewRecord) {
            $this->registration = new CDbExpression('NOW()');

            if (isset($_SERVER['HTTP_USER_AGENT']))
                $this->user_agent = $_SERVER['HTTP_USER_AGENT']; //should really be in the controller\
            else
                $this->user_agent = 'UNAVALAIBLE';
        }



        $airship = new Airship(Yii::app()->params['urbanairship_app_key'], Yii::app()->params['urbanairship_app_master_secret']);
        // associate Urban Airship tags (state and state.district_number)
        try {
            $state_district_tag = $this->stateAbbr->abbr . '_' . $this->district->number;

            //  $airship->add_device_tag($this->stateAbbr->abbr, $this->device_token, $this->type);
            // $airship->add_device_tag($state_district_tag, $this->device_token, $this->type);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }


        if (!$this->latitude)
            $this->latitude = NULL;
        if (!$this->longitude)
            $this->longitude = NULL;

        return parent::beforeSave();
    }

}

