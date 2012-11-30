<?php

Yii::import('application.vendors.*');
// require_once('urbanairship/urbanairship.php');

/**
 * This is the model class for table "app_user".
 *
 * The followings are the available columns in table 'app_users':
 * @property integer $id
 * @property string $device_token
 * @property string $latitude
 * @property string $longitude
 * @property integer $district_id
 * @property string $registration
 * @property string $type
 * @property string $user_agent
 * @property string $uap_user_id
 *
 */
class Application_user extends CActiveRecord {

    public $state_abbr; // not part of the model, here for cgridview (admin search)
    public $district_type; // not part of the model, here for cgridview (admin search)
    public $district_number; // not part of the model, here for cgridview
    private static $device_types = array('ios', 'android');

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Application_user the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'app_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('device_token, district_id, type, registration', 'required'),
            array('district_id', 'numerical', 'integerOnly' => true),
            array('device_token', 'length', 'max' => 128),
            array('user_agent', 'length', 'max' => 1024),
            array('latitude, longitude, registration, type, tags, tenant_account_id', 'safe'),
            array('registration', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('id, device_token, latitude, longitude, state_abbr, district_id, district_type, district_number, registration, type, user_agent', 'safe', 'on' => 'search'),
            array('geolocation', 'geolocation'), // test lat and long format
            array('type', 'device_type'),
        );
    }

    /**
     * Validation rules - test that the given lat and long data is a valid format
     * Set  error field if the lat and long doesnt match a valid format
     */
    public function geolocation() {
        if (isset($this->latitude) && isset($this->longitude) && $this->isNewRecord) {

            if (!preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', $this->latitude) || !preg_match('/^[-+]?[0-9]*\,?[0-9]+$/', $this->longitude)) {
                $this->addError("latlong", 'wrong format');
            }
        }
    }

    /**
     * Validation rules - test the device type
     */
    public function device_type() {
        if (!in_array($this->type, self::$device_types))
            $this->addError('type', 'invalid device type');
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'district' => array(self::BELONGS_TO, 'District', 'district_id'),
            'tags' => array(self::MANY_MANY, 'Tag', 'app_user_tag(app_user_id, tag_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'device_token' => 'Device Token',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'district_id' => 'District',
            'registration' => 'Registration',
            'type' => 'Device Type',
            'user_agent' => 'User Agent',
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
        $criteria->compare('device_token', $this->device_token, true);
        $criteria->compare('latitude', $this->latitude, true);
        $criteria->compare('longitude', $this->longitude, true);
        $criteria->compare('registration', $this->registration, true);
        $criteria->compare('type', $this->type, true);
        $criteria->compare('user_agent', $this->user_agent, true);

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

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->registration = date('Y-m-d H:i:s');
        }

        if (!$this->latitude)
            $this->latitude = NULL;
        if (!$this->longitude)
            $this->longitude = NULL;

        return parent::beforeSave();
    }

    /*
     * Attach external behaviors 
     * @method void test()
     */

    public function behaviors() {
        return array(
            'metaBehavior' => array(
                'class' => 'ApplicationUserMetaBehavior',
            ),
            'tagBehavior' => array(
                'class' => 'ApplicationUserTagBehavior',
            ),
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    public function synchronizeUAPTags() {

        $uap_notifier = new UrbanAirshipNotifier();

        $uap_tags = array(
            $this->district->state_abbr,
            $this->district->state_abbr . '_' . $this->district->type . '_' . $this->district->number,
        );

        foreach ($this->getTagsName() as $tag)
            array_push($uap_tags, $tag);

        return $uap_notifier->updateRichUserTags($this->uap_user_id, $this->device_token, $uap_tags);
    }

    public static function is_valid_type($type) {
        return in_array($type, self::$device_types);
    }

}