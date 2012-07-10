<?php

/**
 * This is the model class for table "ballot_item".
 *
 * The followings are the available columns in table 'ballot_item':
 * @property integer $id
 * @property integer $district_id
 * @property string $item
 * @property string $item_type
 * @property integer $recommendation_id
 * @property string $next_election_date
 * @property integer $priority
 * @property string $detail
 * @property string $date_published
 * @property string $published
 * @property string $party
 * @property string $url
 * @property string $image_url
 * @property integer $election_result_id
 * @property string $personal_url
 * @property string $office_type
 * @property integer score
 * The followings are the available model relations:
 * @property District $district
 * @property Recommendation $recommendation
 * @property Recommendation $electionResult


 */
class BallotItem extends CActiveRecord {

    public $state_abbr; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview (admin search)
    private $labelled_parties = array(
        'N/A' => 'Not Avalaible',
        'democratic' => 'Democratic',
        'republican' => 'Republican',
        'independant' => 'Independant',
    );
    private $office_types = array(
        'N/A' => 'Not Avalaible',
        'representative' => 'Representative',
        'senator' => 'Senator',
        'at_large_delate' => 'At Large Delegate'
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BallotItem the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'ballot_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('district_id, item, recommendation_id, priority, date_published, published, election_result_id', 'required'),
            array('district_id, recommendation_id, priority, election_result_id, score', 'numerical', 'integerOnly' => true),
            array('item_type, party, office_type', 'length', 'max' => 128),
            array('url', 'length', 'max' => 500),
            array('personal_url', 'length', 'max' => 2048),
            array('personal_url', 'url'),
            array('published', 'length', 'max' => 16),
            array('date_published', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('next_election_date', 'date', 'format' => 'yyyy-M-d'),
            array('next_election_date, detail, url, image_url', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, district_id, item,office_type, item_type, recommendation_id, next_election_date, priority, detail, date_published, published, party, url, image_url, election_result_id, district_number, district_type, state_abbr, personal_url, score', 'safe', 'on' => 'search'),
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
            'recommendation' => array(self::BELONGS_TO, 'Recommendation', 'recommendation_id'),
            'electionResult' => array(self::BELONGS_TO, 'Recommendation', 'election_result_id'),
            'BallotItemNews' => array(self::HAS_MANY, 'BallotItemNews', 'ballot_item_id'),
            'Scorecard' => array(self::HAS_MANY, 'Scorecard', 'ballot_item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'district_id' => 'District',
            'item' => 'Ballot Item Name',
            'item_type' => 'Ballot Item Type',
            'recommendation_id' => 'Recommendation',
            'next_election_date' => 'Election Date',
            'priority' => 'Priority',
            'detail' => 'Detail',
            'date_published' => 'Date Published',
            'published' => 'Published',
            'party' => 'Party',
            'url' => 'URL',
            'image_url' => 'Headshot',
            'election_result_id' => 'Election Result',
            'office_type' => 'Office type'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteriaInsensitive();

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
        $criteria->compare('district_id', $this->district_id);
        $criteria->compare('item', $this->item, true);
        $criteria->compare('item_type', $this->item_type, true);
        $criteria->compare('recommendation_id', $this->recommendation_id);
        $criteria->compare('next_election_date', $this->next_election_date, true);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('office_type', $this->office_type, true);
        $criteria->compare('published', $this->published, true);
        $criteria->compare('party', $this->party, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('image_url', $this->image_url, true);
        $criteria->compare('election_result_id', $this->election_result_id);


        // $criteria->compare('date_published', $this->date_published, false);
        if ($this->date_published) {
            // trim white spaces
            $this->date_published = trim($this->date_published);
            // handle users habit to uses / as a separator
            $this->date_published = str_replace('/', '-', $this->date_published);

            // check that the format is yyyy-mm-dd. also checks that the values are correct
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $this->date_published)) {
                $criteria->compare('date_published', '> ' . $this->date_published . ' 00:00:00', false);
            } // else user has just entered a year 2012
            elseif (preg_match('/^[0-9]{4}$/', $this->date_published)) {
                $criteria->compare('date_published', '> ' . $this->date_published . ' 01-01 00:00:00', false);
                $criteria->compare('date_published', '< ' . $this->date_published . ' 12-31 23:59:59', false, 'AND');
            }
        }

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                    'sort' => array(
                        'defaultOrder' => $this->getTableAlias(false, false) . '.id DESC',
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
                                'asc' => 'district.number ASC',
                                'desc' => 'district.number DESC',
                            ),
                            '*',
                    ))
                ));
    }

    /**
     * Return the different priority options
     * @return array array of priority options
     */
    public function getPriorityOptions() {
        return array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
    }

    /**
     * Return the different parties options
     * @return array array of party options
     */
    public function getParties() {
        return $this->labelled_parties;
    }

    /**
     * Return the different office options
     * @return array array of office options
     */
    public function getOfficeTypes() {
        return $this->office_types;
    }

    /**
     * Return the different item options
     * @return array array of item options
     */
    public function getItemTypeOptions() {
        return array(
            'candidate' => 'Candidate',
            'measure' => 'Measure'
        );
    }

    /**
     *  Executed after the form is submitted. Set the next_election_date to null if empty (required by the DB)
     */
    protected function afterValidate() {
        // PostgresSql doesn't support empty strings for Timestamp type columns. Use NULL instead
        if ($this->next_election_date == '')
            $this->next_election_date = null;
    }

    /*
     * Attach external behaviors 
     */

    public function behaviors() {
        return array(
            'beforeSave' => array(
                'class' => 'BallotItemBehavior',
            //  'property1'=>'value1',
            // 'property2'=>'value2',
            ),
        );
    }

}