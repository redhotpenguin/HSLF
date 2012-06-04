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
 * @property string  $slug
 *
 * The followings are the available model relations:
 * @property District $district
 * @property Recommendation $recommendation
 * @property Recommendation $electionResult
 */
class BallotItem extends CActiveRecord {

    public $district_number; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $state_abbr; // not part of the model, here for cgridview (admin search)

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
            array('district_id, item, recommendation_id, priority, date_published, published, election_result_id, slug', 'required'),
            array('district_id, recommendation_id, priority, election_result_id', 'numerical', 'integerOnly' => true),
            array('item_type, party', 'length', 'max' => 128),
            array('slug', 'length', 'max' => 200), // todo: block quotes and double quotes
            array('published', 'length', 'max' => 16),
            array('next_election_date, detail, url, image_url', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, district_id, item, item_type, recommendation_id, next_election_date, priority, detail, date_published, published, party, url, image_url, election_result_id, district_number, district_type, state_abbr, slug', 'safe', 'on' => 'search'),
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
            'slug' => 'Slug'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;
        // search by relationship (district)
        if ($this->district_number || $this->district_type || $this->state_abbr) {
            $criteria->together = true;
            // Join the 'district' table
            $criteria->with = array('district');

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
        $criteria->compare('slug', $this->slug);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 50,
                    ),
                ));
    }

    public function getPriorityOptions() {
        return array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10);
    }

    public function getItemTypeOptions() {
        return array(
            'candidate' => 'Candidate',
            'measure' => 'Measure'
        );
    }

    // executed after the form is validated
    protected function afterValidate() {
        // PostgresSql doesn't support empty strings for Timestamp type columns. Use NULL instead
        if ($this->next_election_date == '')
            $this->next_election_date = null;
    }

    public function findAllByState($state_abbr) {
        $district_ids = District::model()->getIdsByState($state_abbr);

        return $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids));
    }

    public function findAllByDistrictType($state_abbr, $district_type) {

        $district_ids = District::model()->getIdsByDistrictType($state_abbr, $district_type);

        return $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_ids));
    }

    public function findAllByDistrict($state_abbr, $district_type, $district) {
        $district_id = District::model()->findByAttributes(array(
                    'state_abbr' => $state_abbr,
                    'type' => $district_type,
                    'number' => $district,
                ))->id;
        /*
         * todo: business logic
         * include district ids where 
         * district_type = statewide 
         */
        
        if (!$district_id)
            return false;

        return $this->with('district', 'recommendation', 'electionResult')->findAllByAttributes(array('district_id' => $district_id));
    }

    public function findByPublishedYearAndSlug($year, $slug) {
        return $this->findByAttributes(
                        array(
                    'slug' => $slug,
                        ), array('condition' => "date_published BETWEEN '{$year}-01-01 00:00:00' AND '{$year}-12-31 23:59:59'")
        );
    }

}
