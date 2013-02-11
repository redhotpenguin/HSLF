<?php

/**
 * This is the model class for table "item".
 *
 * The followings are the available columns in table 'item':
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
 * @property string $slug
 * @property string $image_url
 * @property string $website
 * The followings are the available model relations:
 * @property District $district
 * @property Recommendation $recommendation
 * @property Recommendation $electionResult
 * @property string facebook_url
 * @property string twitter_handle
 * @property string measure_number
 * @property string friendly_name
 * @property string first_name
 * @Property string last_name


 */
class Item extends BaseActiveRecord {

    public $state_id; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview (admin search)
    public $district_display_name; // not part of the model, here for cgridview (admin search)
    public $party_name; // not part of the model, here for cgridview (admin search)

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Item the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('district_id, item, recommendation_id, date_published, published, slug, item_type, party_id, recommendation_id', 'required'),
            array('district_id, recommendation_id, party_id', 'numerical', 'integerOnly' => true),
            array('item_type, twitter_handle', 'length', 'max' => 128),
            array('friendly_name, first_name, last_name', 'length', 'max' => 1024),
            array('measure_number', 'length', 'max' => 24),
            array('slug', 'length', 'max' => 500),
            array('website, facebook_url', 'length', 'max' => 2048),
            array('website, facebook_url', 'url'),
            array('published', 'length', 'max' => 16),
            array('date_published', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('next_election_date', 'date', 'format' => 'yyyy-M-d'),
            array('next_election_date, detail, slug, image_url', 'safe'),
            array('slug', 'unique_url'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, district_id, item, item_type, recommendation_id, next_election_date, detail, date_published, published, party_id, url, image_url, election_result_id, district_number, district_type,district_display_name, state_id, website, party, facebook_url, twitter_handle, measure_number, friendly_name, first_name, last_name', 'safe', 'on' => 'search'),
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
            'itemNews' => array(self::HAS_MANY, 'ItemNews', 'item_id'),
            'party' => array(self::BELONGS_TO, 'Party', 'party_id'),

            'itemOrganizations' => array(self::HAS_MANY, 'ItemOrganization', 'item_id'),
            
            'organizations' => array(self::MANY_MANY, 'Organization',
                'organization_item(item_id, organization_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'district_id' => 'District',
            'item' => 'Name',
            'item_type' => 'Type',
            'recommendation_id' => 'Recommendation',
            'next_election_date' => 'Election Date',
            'detail' => 'Detail',
            'date_published' => 'Date Published',
            'published' => 'Published',
            'party_id' => 'Party',
            'slug' => 'Slug',
            'image_url' => 'Image URL',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteriaInsensitive();

        $criteria->with = array('district',  'party');

        // search by relationship (district)
        if ($this->district_number || $this->district_type || $this->district_display_name || $this->state_id  || $this->party) {
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
                'class' => 'ItemBehavior',
            ),
            'OrganizationBehavior' => array(
                'class' => 'ItemOrganizationBehavior',
            ),
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    /**
     * Validation rules - make sure the item url is unique
     * @param string $attribute model attribute
     */
    public function unique_url($attribute) {
        if ($this->isNewRecord && !empty($this->url)) {
            $items = Item::model()->findAllByAttributes(
                    array('url' => $this->url)
            );

            if ($items) {
                $this->addError($attribute, 'The url is already taken.');
            }
        }
    }

}