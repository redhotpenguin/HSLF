<?php

/**
 * This is the model class for table "district".
 *
 * The followings are the available columns in table 'district':
 * @property integer $id
 * @property string $state_abbr
 * @property integer $number
 * @property string  $type
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 */
class District extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return District the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'district';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('state_abbr, type', 'required'),
            array('number', 'length', 'max' => 512),
            array('state_abbr', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, state_abbr, number, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'candidates' => array(self::HAS_MANY, 'Candidate', 'district_id'),
            'Application_users' => array(self::HAS_MANY, 'Application_users', 'district_id'),
            'user_alerts' => array(self::HAS_MANY, 'User_alert', 'district_id'),
            'stateAbbr' => array(self::BELONGS_TO, 'State', 'state_abbr'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'state_abbr' => 'State Abbreviation',
            'number' => 'District Number',
            'type' => 'District Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('state_abbr', $this->state_abbr, true);
        $criteria->compare('number', $this->number);
        $criteria->compare('type', $this->type);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                    'sort' => array(
                        'defaultOrder' => 'state_abbr ASC')
                ));
    }

    public static function getTagDistrictsByState($state_abbr) {
        $districts = District::model()->findAllByAttributes(array('state_abbr' => $state_abbr), array('order' => 'number ASC'));
        return CHtml::listData($districts, 'id', 'number');
    }

    public static function getTagDistrictsByStateAndType($state_abbr, $district_type) {
        $districts = District::model()->findAllByAttributes(array('state_abbr' => $state_abbr, 'type' => $district_type), array('order' => 'number ASC'));
        foreach ($districts as $district) {
            if (empty($district->number)) {
                $district->number = 'N/A';
            }
        }

        return CHtml::listData($districts, 'id', 'number');
    }

    /**
     * Return the different district type  options
     * @return array array of type options
     */
    public function getTypeOptions() {
        return array(
            'statewide' => 'Statewide',
            'congressional' => 'Congressional',
            'upper_house' => 'Upper House',
            'lower_house' => 'Lower House',
            'county' => 'County',
            'city' => 'City',
        );
    }

    /**
     * Retrieve the District ID based on $state and $district_number
     */
    public static function getIdByStateAndDistrict($state, $district_number) {
        $district = District::model()->findByAttributes(array('state_abbr' => $state, 'number' => $district_number));
        if ($district)
            return $district->id;
        else
            return false;
    }

    /**
     * Get all the district ids within a state
     * @param integer $state_abbr  abbreviaiton of the state
     * @return array array of district ids
     */
    public function getIdsByState($state_abbr) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr', array(':state_abbr' => $state_abbr))
                ->queryAll();

        return array_map(array(&$this, 'extract_id'), $result);
    }

    /**
     * Get all the district ids within a state and of a specified type
     * @param integer $state_abbr  abbreviaiton of the state
     * @param integer $district  district type
     * @return array array of district ids
     */
    public function getIdsByDistrictType($state_abbr, $district_type) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr AND type=:district_type', array(':state_abbr' => $state_abbr, ':district_type' => $district_type))
                ->queryAll();

        return array_map(array(&$this, 'extract_id'), $result);
    }

    private function extract_id($a) {
        return $a['id'];
    }

    /**
     * Get all the district ids that match a specified state, a specified type and a speficied district number
     * @param integer $state_abbr  abbreviaiton of the state
     * @param integer $type  district type
     * * @param integer $district  district name
     * @return array array of district ids
     */
    public function getIdsByDistrict($state_abbr, $district_type, $district) {
        $command = Yii::app()->db->createCommand();

        $result = $command->select('id')
                ->from('district')
                ->where('state_abbr=:state_abbr AND type=:district_type AND number=:district_number', array(':state_abbr' => $state_abbr, ':district_type' => $district_type, ':district_number' => $district))
                ->queryAll();

        return array_map(array(&$this, 'extract_id'), $result);
    }

    /**
     * Get all the district ids within a state and for multiple district types
     * @param integer $state_abbr  abbreviaiton of the state
     * @param $district_types  array of district types
     * @return array array of district ids
     */
    public function getIdsByDistrictTypes($state_abbr, array $district_types) {
        /*
         * district_types = array('statewide,'congressional', 'upper_house', ... )
         */
        
        $command = Yii::app()->db->createCommand();

     
         /*
         * In order to query the district table using condition logic
         * add they keyword 'type' in front of the district type
         *  array( type='statewide', type='congressional', type='upper_house', .... )
         */
        $district_types = array_map(array(&$this, 'addType'), $district_types);
        
        
        /*
         *  add the element 'OR' on top of the array
         *  array( 'OR ', type='statewide', type='congressional', type='upper_house', .... )
         */
        array_unshift($district_types, 'OR');


        $result = $command->select('id')
                ->from('district')
                // ->where('state_abbr=:state_abbr AND type=:district_type', array(':state_abbr' => $state_abbr, ':district_type' => 'statewide'))
                ->where(array('and', "state_abbr='$state_abbr'", $district_types ))
                ->queryAll();

        return array_map(array(&$this, 'extract_id'), $result);
    }
    
      /**
       * Prepend the keyword 'type' to an element
       * @param type $a
       * @return type 
       */
      function addType($a) {
        return 'type=' . "'$a'";
    }


}