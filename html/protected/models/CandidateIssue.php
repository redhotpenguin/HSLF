<?php

/**
 * This is the model class for table "candidate_issue".
 *
 * The followings are the available columns in table 'candidate_issue':
 * @property integer $id
 * @property integer $candidate_id
 * @property string $name
 * @property string $value
 * @property string $detail
 *
 * The followings are the available model relations:
 * @property Candidate $candidate
 */
class CandidateIssue extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return CandidateIssue the static model class
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
		return 'candidate_issue';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('candidate_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>1024),
			array('value', 'length', 'max'=>128),
			array('detail', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, candidate_id, name, value, detail', 'safe', 'on'=>'search'),
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
			'candidate' => array(self::BELONGS_TO, 'Candidate', 'candidate_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'candidate_id' => 'Candidate',
			'name' => 'Name',
			'value' => 'Value',
			'detail' => 'Detail',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('candidate_id',$this->candidate_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('detail',$this->detail,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public function getTemplatizedIssues( $candidate_id )  {
           $candidate_issues =  $this->findAllByAttributes(  array('candidate_id'=>$candidate_id) );
           $templetized_issues = array();
           
           foreach($candidate_issues as $candidate_issue){
            
               array_push($templetized_issues, array(
                   'name'=> $candidate_issue->name,
                   'value'=>$candidate_issue->value,
                   'detail'=>$candidate_issue->detail,
               ));
               
               
           }
           
           
           
           return $templetized_issues;
        }
}