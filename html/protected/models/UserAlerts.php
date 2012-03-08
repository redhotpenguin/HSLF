<?php

/**
 * This is the model class for table "user_alerts".
 *
 * The followings are the available columns in table 'user_alerts':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $state_abbr
 * @property integer $district_number
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 * @property District $districtNumber
 */
class UserAlerts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserAlerts the static model class
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
		return 'user_alerts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, content, state_abbr, district_number', 'required'),
			array('district_number', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>1024),
			array('state_abbr', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, state_abbr, district_number', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'content' => 'Content',
			'state_abbr' => 'State Abbr',
			'district_number' => 'District Number',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('state_abbr',$this->state_abbr,true);
		$criteria->compare('district_number',$this->district_number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}