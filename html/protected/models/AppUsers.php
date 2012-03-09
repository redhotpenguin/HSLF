<?php

/**
 * This is the model class for table "app_users".
 *
 * The followings are the available columns in table 'app_users':
 * @property string $id
 * @property string $device_token
 * @property double $latitude
 * @property double $longitude
 * @property string $state_abbr
 * @property integer $district_number
 * @property string $registration
 * @property string $type
 * @property string $user_agent
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 * @property District $districtNumber
 */
class AppUsers extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AppUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'app_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('device_token, state_abbr, district_number, type', 'required'),
			array('district_number', 'numerical', 'integerOnly'=>true),
			array('latitude, longitude', 'numerical'),
			array('device_token', 'length', 'max'=>128),
			array('state_abbr', 'length', 'max'=>2),
			array('user_agent', 'length', 'max'=>1024),
                         array('type', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, device_token, latitude, longitude, state_abbr, district_number, registration, user_agent', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'stateAbbr' => array(self::BELONGS_TO, 'State', 'state_abbr'),
			'districtNumber' => array(self::BELONGS_TO, 'District', 'district_number'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'device_token' => 'Device Token',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'state_abbr' => 'State Abbr',
			'district_number' => 'District Number',
			'registration' => 'Registration',
                        'type' => 'Type',
			'user_agent' => 'User Agent',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('device_token',$this->device_token,true);
		$criteria->compare('latitude',$this->latitude);
		$criteria->compare('longitude',$this->longitude);
		$criteria->compare('state_abbr',$this->state_abbr,true);
		$criteria->compare('district_number',$this->district_number);
		$criteria->compare('registration',$this->registration,true);
                $criteria->compare('type',$this->type,true);
		$criteria->compare('user_agent',$this->user_agent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function beforeSave(){
              if ($this->isNewRecord){
                     $this->registration = new CDbExpression('NOW()');
                     $this->user_agent = $_SERVER['HTTP_USER_AGENT']; //should really be in the controller
              }

 
                return parent::beforeSave();
        }
}