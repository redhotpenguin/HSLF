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
 * @property string $detail
 * @property string $date_published
 * @property string $published
 * @property string $party_id
 * @property string $url
 * @property string $image_url
 * @property integer $election_result_id
 * @property string $personal_url
 * @property integer score
 * @property integer office_id
 * The followings are the available model relations:
 * @property District $district
 * @property Recommendation $recommendation
 * @property Recommendation $electionResult
 * @property string facebook_url
 * @property string facebook_share
 * @property string twitter_handle
 * @property string twitter_share
 * @property string hold_office
 * @property string measure_number
 * @property string friendly_name
 * @property string keywords


 */
class BallotItem extends CBaseActiveRecord {

    public $state_id; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview (admin search)
    public $district_display_name; // not part of the model, here for cgridview (admin search)
    public $office_type; // not part of the model, here for cgridview (admin search)
    public $party_name; // not part of the model, here for cgridview (admin search)

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
            array('district_id, item, recommendation_id, office_id, date_published, published, election_result_id, url', 'required'),
            array('district_id, recommendation_id, election_result_id, score, party_id', 'numerical', 'integerOnly' => true),
            array('item_type, twitter_handle', 'length', 'max' => 128),
            array('facebook_share, friendly_name', 'length', 'max' => 1024),
            array('measure_number', 'length', 'max' => 24),
            array('twitter_share', 'length', 'max' => 140),
            array('url', 'length', 'max' => 500),
            array('personal_url, facebook_url', 'length', 'max' => 2048),
            array('personal_url, facebook_url', 'url'),
            array('published, hold_office', 'length', 'max' => 16),
            array('date_published', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('next_election_date', 'date', 'format' => 'yyyy-M-d'),
            array('next_election_date, detail, url, image_url, keywords, tenant_id', 'safe'),
            array('url', 'unique_url'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, district_id, item, item_type, recommendation_id, next_election_date, detail, date_published, published, party_id, url, image_url, election_result_id, district_number, district_type,district_display_name, state_id, personal_url, score, office_type, party, facebook_url, facebook_share, twitter_handle, twitter_share, hold_office, measure_number, friendly_name,keywords', 'safe', 'on' => 'search'),
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
            'ballotItemNews' => array(self::HAS_MANY, 'BallotItemNews', 'ballot_item_id'),
            'scorecards' => array(self::HAS_MANY, 'Scorecard', 'ballot_item_id'),
            'cards' => array(self::MANY_MANY, 'ScorecardItem',
                'scorecard(ballot_item_id, scorecard_item_id)'),
            'office' => array(self::BELONGS_TO, 'Office', 'office_id'),
            'party' => array(self::BELONGS_TO, 'Party', 'party_id'),
            //'endorsers' => array(self::MANY_MANY, 'Endorser',
            //'endorser_ballot_item(endorser_id, ballot_item_id)'),


            'ballotItemEndorsers' => array(self::HAS_MANY, 'BallotItemEndorser', 'ballot_item_id'),
            'endorsers' => array(self::MANY_MANY, 'Endorser',
                'endorser_ballot_item(ballot_item_id, endorser_id)'),
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
            'detail' => 'Detail',
            'date_published' => 'Date Published',
            'published' => 'Published',
            'party_id' => 'Party',
            'url' => 'URL',
            'image_url' => 'Image',
            'election_result_id' => 'Election Result',
            'office_id' => 'Office',
            'office_type' => 'Office',
            'facebook_share' => 'Facebook Share Text',
            'twitter_share' => 'Twitter Share Text',
            'keywords' => 'Keywords'
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteriaInsensitive();

        $criteria->with = array('district', 'office', 'party');

        // search by relationship (district)
        if ($this->district_number || $this->district_type || $this->district_display_name || $this->state_id || $this->office_type || $this->party) {
            $criteria->together = true;
            // Join the 'district' table
            if ($this->district_number)
                $criteria->compare('district.number', $this->district_number, false);
            if ($this->district_type)
                $criteria->compare('district.type', $this->district_type, true);
            if ($this->state_id)
                $criteria->compare('district.state_id', $this->state_id, false);

            if ($this->district_display_name)
                $criteria->compare('district.display_name', $this->district_display_name, true);

            if ($this->office_type)
                $criteria->compare('office.name', $this->office_type, false);

            if ($this->party)
                $criteria->compare('party.name', $this->party, false);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('district_id', $this->district_id);
        $criteria->compare('item', $this->item, true);
        $criteria->compare('recommendation_id', $this->recommendation_id);
        $criteria->compare('next_election_date', $this->next_election_date, true);
        $criteria->compare('detail', $this->detail, true);
        $criteria->compare('published', $this->published, true);
        $criteria->compare('party_id', $this->party_id, true);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('image_url', $this->image_url, true);
        $criteria->compare('election_result_id', $this->election_result_id);


        $criteria->compare('item_type', $this->item_type);


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
                            'state_id' => array(
                                'asc' => 'district.state_id',
                                'desc' => 'district.state_id DESC',
                            ),
                            'district_type' => array(
                                'asc' => 'district.type',
                                'desc' => 'district.type DESC',
                            ),
                            'district_number' => array(
                                'asc' => 'district.number ASC',
                                'desc' => 'district.number DESC',
                            ),
                            'office_type' => array(
                                'asc' => 'office.name ASC',
                                'desc' => 'office.name DESC',
                            ),
                            '*',
                    ))
                ));
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
            'EndorserBehavior' => array(
                'class' => 'BallotItemEndorserBehavior',
            ),
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    /**
     * Validation rules - make sure the ballot item url is unique
     * @param string $attribute model attribute
     */
    public function unique_url($attribute) {
        if ($this->isNewRecord && !empty($this->url)) {
            $ballots = BallotItem::model()->findAllByAttributes(
                    array('url' => $this->url)
            );

            if ($ballots) {
                $this->addError($attribute, 'The url is already taken.');
            }
        }
    }

}