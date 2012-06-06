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
 * The followings are the available model relations:
 * @property District $district
 * @property Recommendation $recommendation
 * @property Recommendation $electionResult
 */
class BallotItem extends CActiveRecord {

    public $state_abbr; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview (admin search)
    private $all_district_types = array('statewide', 'congressional', 'upper_house', 'lower_house', 'county', 'city');

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
            array('district_id, recommendation_id, priority, election_result_id', 'numerical', 'integerOnly' => true),
            array('item_type, party', 'length', 'max' => 128),
            array('url', 'length', 'max' => 500),
            array('published', 'length', 'max' => 16),
            array('next_election_date, detail, url, image_url', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, district_id, item, item_type, recommendation_id, next_election_date, priority, detail, date_published, published, party, url, image_url, election_result_id, district_number, district_type, state_abbr', 'safe', 'on' => 'search'),
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
            'image_url' => 'Image URL',
            'election_result_id' => 'Election Result',
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
        $criteria->compare('district_id', $this->district_id);
        $criteria->compare('item', $this->item, true);
        $criteria->compare('item_type', $this->item_type, true);
        $criteria->compare('recommendation_id', $this->recommendation_id);
        $criteria->compare('next_election_date', $this->next_election_date, true);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('date_published', $this->date_published, true);
        $criteria->compare('published', $this->published, true);
        $criteria->compare('party', $this->party, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('image_url', $this->image_url, true);
        $criteria->compare('election_result_id', $this->election_result_id);

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

    /**
     * Return the different priority options
     * @return array array of priority options
     */
    public function getPriorityOptions() {
        return array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
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

    /**
     * Find all the ballot models by state
     * @param string $state_abbr abbreviation of the state
     * @return array return array of ballot items
     */
    public function findAllByState($state_abbr) {
        $district_ids = District::model()->getIdsByState($state_abbr);

        $ballots = $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids));

        return self::applyFilter($ballots);
    }

    /**
     * Find all the ballot models by state and by type. Can also include statewide districts
     * @param string $state_abbr abbreviation of the state
     * @param string $district_type type of the district
     * @param bool  $include_state_wide_district if true, include state wide districts
     * @return array return array of ballot items
     */
    public function findAllByDistrictType($state_abbr, $district_type, $include_state_wide_district = false) {
        $district_ids = District::model()->getIdsByDistrictType($state_abbr, $district_type);

        if ($include_state_wide_district) {
            $state_district_ids = District::model()->getIdsByDistrictType($state_abbr, 'statewide');
            // include state wide districts    
            $district_ids = array_merge($district_ids, $state_district_ids);
        }

        $ballots = $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids, 'published' => 'yes'));
        return self::applyFilter($ballots);
    }

    /**
     * Find all the ballot models by state, by type and by district name. Can also include statewide districts
     * @param string $state_abbr abbreviation of the state
     * @param string $district_type type of the district
     * @param string $district name of the district
     * @param bool  $include_state_wide_district if true, include state wide districts
     * @return array return array of ballot items
     */
    public function findAllByDistrict($state_abbr, $district_type, $district, $include_larger_districts = false) {

        $district_id = District::model()->findByAttributes(array(
                    'state_abbr' => $state_abbr,
                    'type' => $district_type,
                    'number' => $district,
                ))->id;

        if ($include_larger_districts) {

            // find the district types larger (or equal) than $district_type (cant be superior than statewide yet)
            if ($district_type != 'statewide') {
                $position = array_search($district_type, $this->all_district_types);
                $larger_district_types = array_slice($this->all_district_types, 0, $position);

                // include the request district_type
                $larger_district_types = array_merge ($larger_district_types, array($district_type));
            } else {
                $larger_district_types = array('statewide');
            }

            $districts = District::model()->getIdsByDistrictTypes($state_abbr, $larger_district_types);
            
        }else
            $districts = array($district_id);

        if (!$district_id)
            return false;

        $ballots = $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $districts, 'published' => 'yes'));

        return self::applyFilter($ballots);
    }

    /**
     * Find a unique  ballot model by the year and url
     * @param integer $year year of the ballot was published
     * @param string url  url of the ballot model
     * @return object return a ballot_item object
     */
    public function findByPublishedYearAndUrl($year, $url) {
        return $this->findByAttributes(
                        array(
                    'url' => $url,
                        ), array('condition' => "date_published BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59' AND published='yes' ")
        );
    }

    /**
     * Apply set of filters to an array of ballot items
     * @param array $ballots array of ballotItem
     * @return array of filtered ballotItem
     */
    private static function applyFilter($ballots) {
        if (is_array($ballots)) {
            foreach ($ballots as $ballot) {
                $ballot->url = BallotItem::addSiteUrlFilter($ballot->url);
            }
        } else {
            $ballots->url = BallotItem::addSiteUrlFilter($ballot->url);
        }

        return $ballots;
    }

    /**
     * Prepend the share_url option to the url field
     * @param object $BallotItem BallotITem object
     * @return string filtered ballot item
     */
    private static function addSiteUrlFilter($ballot_url) {
        $share_url = Yii::app()->params['share_url'] . '/ballot';

        return $share_url . '/' . date('Y') . '/' . $ballot_url;
    }

}