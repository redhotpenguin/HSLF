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
 * @property string $url	
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
            array('district_id, scorecard', 'numerical', 'integerOnly' => true),
            array('state_abbr', 'length', 'max' => 3),
            array('full_name', 'length', 'max' => 256),
            array('party, publish', 'length', 'max' => 128),
            array('type, endorsement, date_published, scorecard, url, issues', 'safe'),
            array('date_published', 'date', 'format' => 'yyyy-M-d H:m:s'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, state_abbr, district_id, type, endorsement, full_name, party, date_published, publish, district_number, issues, url', 'safe', 'on' => 'search'),
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
            'issues' => array(self::HAS_MANY, 'CandidateIssue', 'candidate_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'state_abbr' => 'State',
            'district_id' => 'District',
            'type' => 'Type',
            'endorsement' => 'Endorsement',
            'full_name' => 'Full Name',
            'party' => 'Party',
            'date_published' => 'Date Published',
            'publish' => 'Publish',
            'district_number' => 'District number',
            'candidate_url_field' => 'Candidate url',
            'issues'=>'Issues'
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
        } else {
            $criteria->together = false;
            $criteria->with = array();
            $criteria->compare('state_abbr', $this->state_abbr, true);
        }

        if (!is_numeric($this->id)) {
            $this->id = '';
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('endorsement', $this->endorsement, true);
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('party', $this->party, true);

        $criteria->compare('publish', $this->publish, true);

        $criteria->compare("date_published", $this->date_published, false);
        // error_log($this->date_published);
        //$criteria->addCondition("date_published = to_date( '".$this->date_published."', 'YYYY') ");
        //SELECT * FROM candidate WHERE date_published < to_date('2012', 'YYYY');


        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array('pageSize' => 50),
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

    /**
     * Generate an html view of the issues
     * @return string
     */
    public function getHTMLIssues() {
       $html_issues = '<ul>';
       
       foreach($this->issues as $issue){
           $html_issues.='<li style="margin-bottom:15px;">';
           $html_issues.=$issue->name.'<br/>';
           $html_issues.=$issue->value.'<br/>';
           $html_issues.=$issue->detail.'<br/>';
           
           $html_issues.='</li>';
       }
       
       return $html_issues.'</ul>';
    }

}