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
    
    
    // district types. Please update getTypeOptions() as well if you modify this list
    private static $district_types = array(
        'statewide',
        'congressional',
        'upper_house',
        'lower_house',
        'county',
        'city',
    );

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
     * Executed before a District is saved
     * Check that there isn't already a district with the same state, type and number (this should be done in the DB)
     */
    public function beforeSave() {

        if ($this->findByAttributes(array(
                    "state_abbr" => $this->state_abbr,
                    "type" => $this->type,
                    "number" => $this->number
                ))) {
            $this->addError('number', "This district already exist");
            return false;
        }
        return parent::beforeSave();
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
     * Retrieve the District ID based on state, type and district name
     * @param string $state_abbr  abbreviaton of the state
     * @param string $type  district type
     * @param string $district  district name
     * return district id
     */
    public static function getDistrictId($state_abbr, $type, $district) {
        $district = District::model()->findByAttributes(array('state_abbr' => $state_abbr, 'type' => $type, 'number' => $district));
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
     * @param string $state_abbr  abbreviaton of the state
     * @param string $type  district type
     * @param string $district  district name
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
     * Get all the district ids that match a specified state,specified types and speficied district numbers
     * @param string  $state_abbr  abbreviaton of the state
     * @param array  $district_types  district types
     * @param array $districts  district names
     * @return array array of district ids
     */
    public function getIdsByDistricts($state_abbr, array $district_types, array $districts) {
        $command = Yii::app()->db->createCommand();
        // verify that $district_types and $districts have the same number of elements
        if (count($district_types) != count($districts))
            return false;

        /*
         * ex:
         * $condistion_string = 'state_abbr=:state_abbr  AND type=:district_type0 AND number=:district0 OR type=:district_type1 AND number=:district1';
         * 
          $condition_values = array(
          ':state_abbr' => $state_abbr,
          ':district_type0' => 'city',
          ':district0' => 'portland',
          ':district_type1' => 'county',
          ':district1' => 'clackamas'
          );
         * 
         */
        // construct the parameters needed for the query (see example above)
        $condition_string = 'state_abbr=:state_abbr ';
        $condition_values = array(':state_abbr' => $state_abbr);

        foreach ($district_types as $i => $district_type) {
            if ($i > 0)
                $condition_string .= ' OR state_abbr=:state_abbr AND type=:district_type' . $i . ' AND number=:district' . $i;
            else
                $condition_string .= ' AND state_abbr=:state_abbr AND type=:district_type' . $i . ' AND number=:district' . $i;

            // needed because some district types don't have a district number. Ex: statewide
            if (empty($districts[$i]))
                $districts[$i] = '';

            $condition_values[":district_type{$i}"] = $district_type;
            $condition_values[":district{$i}"] = $districts[$i];
        }

        // execute the command
        $result = $command->select('id')
                ->from('district')
                //- WHERE state_abbr 
                ->where($condition_string, $condition_values)
                ->queryAll();


        if ($result)
        // return flat array
            return array_map(array(&$this, 'extract_id'), $result);

        else
            return false;
    }

    /**
     * Prepend the keyword 'type' to an element
     * @param type $a
     * @return type 
     */
    function addType($a) {
        return 'type=' . "'$a'";
    }

    /**
     * Check if the given district type is valid
     * @param string $district_type district type
     * @return true or false
     */
    public static function isValidDistrictType($district_type) {
        return in_array($district_type, self::$district_types);
    }

}