<?php

/**
 * This is the model class for table "user_alerts".
 *
 * The followings are the available columns in table 'user_alerts':
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $state_abbr
 * @property integer $district_id
 * @property string $create_time
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 * @property District $district
 */
class User_alert extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User_alert the static model class
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
			array('title, content', 'required'),
			array('district_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>1024),
			array('state_abbr', 'length', 'max'=>3),
			array('create_time', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, content, state_abbr, district_id, create_time', 'safe', 'on'=>'search'),
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
			'district' => array(self::BELONGS_TO, 'District', 'district_id'),
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
			'district_id' => 'District',
			'create_time' => 'Create Time',
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
		$criteria->compare('district_id',$this->district_id);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}