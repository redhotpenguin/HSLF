<?php

/**
 * This is the model class for table "scorecard_item".
 *
 * The followings are the available columns in table 'scorecard_item':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $office_id
 *
 * The followings are the available model relations:
 * @property Scorecard[] $scorecards
 * @property Office $office
 */
class ScorecardItem extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ScorecardItem the static model class
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
		return 'scorecard_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, office_id', 'required'),
			array('office_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>4096),
			array('description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, office_id', 'safe', 'on'=>'search'),
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
			'scorecards' => array(self::HAS_MANY, 'Scorecard', 'scorecard_item_id'),
			'office' => array(self::BELONGS_TO, 'Office', 'office_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'description' => 'Description',
			'office_id' => 'Office',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('office_id',$this->office_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}