<?php

/**
 * This is the model class for table "district".
 *
 * The followings are the available columns in table 'district':
 * @property integer $id
 * @property integer $state_id
 * @property integer $number
 * @property string  $type
 * @property string $display_name
 * @property string $locality
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 */
class District extends CActiveRecord {


    public static $district_types = array(
        'NATIONAL_EXEC' => 'National Executive',
        'NATIONAL_UPPER' => 'National Upper',
        'NATIONAL_LOWER' => 'National Lower',
        // statewide
        'STATE_EXEC' => 'Statewide Executive',
        'STATE_UPPER' => 'Statewide Upper',
        'STATE_LOWER' => 'Statewide Lower',
        // locals
        'LOCAL_EXEC' => 'Local Executive',
        'LOCAL' => 'Local',
        // non legislative districts:
        'CENSUS' => 'Census',
        'COUNTY' => 'County',
        'SCHOOL' => 'School',
        'JUDICIAL' => 'Judicial',
        'POLICE' => 'Police',
        'WATERSHED' => 'Watershed'
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
            array('state_id, type', 'required'),
            array('number, display_name', 'length', 'max' => 512),
            array('locality', 'length', 'max' => 1024),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, number, type, display_name, locality', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'state' => array(self::BELONGS_TO, 'State', 'state_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'state_id' => 'State',
            'number' => 'District Number',
            'type' => 'District Type',
            'locality' => 'Locality',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = new CDbCriteriaInsensitive();


        $criteria->compare('id', $this->id);

        if ($this->state_id != "") {
            $criteria->compare('state_id', $this->state_id, false);
        }

        $criteria->compare('number', $this->number, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('locality', $this->locality, true);
        $criteria->compare('display_name', $this->display_name, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                        //  'sort' => array(
                        // 'defaultOrder' => 'state_id ASC')
                ));
    }

    /**
     * Executed before a District model is saved or updated
     * Make sure that the district type is valid
     */
    public function beforeSave() {
        if ($this->isValidDistrictType($this->type))
            return parent::beforeSave();
        else {
            $this->addError('type', 'Invalid district type');
            return false;
        }
    }

    /**
     *   Save a District model
     */
    public function save($runValidation = true, $attributes = NULL) {
        try {
            $save_result = parent::save();
        } catch (CDbException $cdbe) {
            switch ($cdbe->getCode()) {
                case 23505:
                    $this->addError('number', 'This district already exist');
                    break;

                default: // we can't handle the error, rethrow it!
                    throw $cdbe;
            }
        }

        return $save_result;
    }

    /**
     * Return the different district type  options
     * @return array array of type options
     */
    public function getTypeOptions() {
        return self::$district_types;
    }

    /**
     * Check if the given district type is valid
     * @param string $district_type district type
     * @return true or false
     */
    public static function isValidDistrictType($district_type) {
        return key_exists($district_type, self::$district_types);
    }

    /*
     * Attach external behaviors 
     */

    public function behaviors() {
        return array(
            'DistrictBehavior' => array(
                'class' => 'DistrictBehavior',
            ),
        );
    }

}