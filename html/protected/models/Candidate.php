<?php

/**
 * This is the model class for table "candidate".
 *
 * The followings are the available columns in table 'candidate':
 * @property integer $id
 * @property string $state_abbr
 * @property integer $district_id
 * @property string $type
 * @property string $endorsement
 * @property string $full_name
 * @property string $party
 * @property string $date_published
 * @property string $publish
 * @property integer $scorecard
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 * @property District $district
 */
class Candidate extends CActiveRecord {

    public $district_number; // not part of the model, here for cgridview

    const TYPE_SENATOR = 'senator';
    const TYPE_REPRESENTATIVE = 'representative';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Candidate the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'candidate';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('state_abbr,district, full_name, party,type,endorsement,date_published, publish , scorecard', 'required'),
            array('district_id', 'numerical', 'integerOnly' => true),
            array('state_abbr', 'length', 'max' => 3),
            array('full_name', 'length', 'max' => 256),
            array('party, publish', 'length', 'max' => 128),
            array('type, endorsement, date_published, scorecard', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, state_abbr, district_id, type, endorsement, full_name, party, date_published, publish, district_number', 'safe', 'on' => 'search'),
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
            'state_abbr' => 'State Abbr',
            'district_id' => 'District',
            'type' => 'Type',
            'endorsement' => 'Endorsement',
            'full_name' => 'Full Name',
            'party' => 'Party',
            'date_published' => 'Date Published',
            'publish' => 'Publish',
            'district_number' => 'District number'
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
            $criteria->compare('district.state_abbr', $this->state_abbr, true);
          //  $criteria->compare('candidate.type', $this->type, false);
        } else {
            $criteria->together = false;
            $criteria->with = array();

            $criteria->compare('id', $this->id);
            $criteria->compare('state_abbr', $this->state_abbr, true);
            $criteria->compare('district_id', $this->district_id);
            $criteria->compare('type', $this->type, true);
            $criteria->compare('endorsement', $this->endorsement, true);
            $criteria->compare('full_name', $this->full_name, true);
            $criteria->compare('party', $this->party, true);
            $criteria->compare('date_published', $this->date_published, true);
            $criteria->compare('publish', $this->publish, true);
        }



        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Retrieves a list of candidate type
     */
    public function getTypeOptions() {
        return array(
            self::TYPE_REPRESENTATIVE => 'Representative',
            self::TYPE_SENATOR => 'Senator',
        );
    }

}