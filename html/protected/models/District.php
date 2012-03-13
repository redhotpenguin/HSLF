<?php

/**
 * This is the model class for table "district".
 *
 * The followings are the available columns in table 'district':
 * @property integer $id
 * @property string $state_abbr
 * @property integer $number
 *
 * The followings are the available model relations:
 * @property State $stateAbbr
 */
class District extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return District the static model class
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
		return 'district';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('state_abbr, number', 'required'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('state_abbr', 'length', 'max'=>2),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, state_abbr, number', 'safe', 'on'=>'search'),
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
			'candidates' => array(self::HAS_MANY, 'Candidate', 'district_id'),
                        'user_alerts' => array(self::HAS_MANY, 'User_alert', 'district_id'),
			'stateAbbr' => array(self::BELONGS_TO, 'State', 'state_abbr'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'state_abbr' => 'State Abbreviation',
			'number' => 'District Number',
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
		$criteria->compare('state_abbr',$this->state_abbr,true);
		$criteria->compare('number',$this->number);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
	 * Retrieve the District ID based on $state and $district_number
	 */
     
      
         static function getIdByStateAndDistrict($state, $district_number){
            $district =  District::model()->findByAttributes( array ( 'state_abbr' => $state,'number' => $district_number ) );
            if($district)
                return $district->id;
            else 
                return false;
         }
}