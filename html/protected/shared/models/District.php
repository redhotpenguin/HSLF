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
                    'sort' => array(
                        'defaultOrder' => 'state_abbr ASC')
                ));
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

    public static function getTagDistrictsByState($state_abbr) {
        $districts = District::model()->findAllByAttributes(array('state_abbr' => $state_abbr), array('order' => 'number ASC'));
        return CHtml::listData($districts, 'id', 'number');
    }
    
    public static function getTagDistrictsByStateAndType($state_abbr, $district_type) {
        $districts = District::model()->findAllByAttributes(array('state_abbr' => $state_abbr, 'type'=>$district_type), array('order' => 'number ASC'));
        foreach($districts as $district){
           if(empty($district->number) ){
               $district->number = 'N/A';
           }
        }
        
        return CHtml::listData($districts, 'id', 'number');
    }


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

}