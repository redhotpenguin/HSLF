<?php

/**
 * This is the model class for table "scorecard".
 *
 * The followings are the available columns in table 'scorecard':
 * @property integer $id
 * @property integer $ballot_item_id
 * @property integer $scorecard_item_id
 * @property string $vote_id
 *
 * The followings are the available model relations:
 * @property BallotItem $ballotItem
 * @property ScorecardItem $scorecardItem
 */
class Scorecard extends CBaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Scorecard the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'scorecard';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ballot_item_id, scorecard_item_id, vote_id', 'required'),
            array('ballot_item_id, scorecard_item_id, vote_id ', 'numerical', 'integerOnly' => true),
            array('tenant_id', 'safe'),
            array('id, ballot_item_id, scorecard_item_id, vote_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'vote' => array(self::BELONGS_TO, 'Vote', 'vote_id'),
            'scorecardItem' => array(self::BELONGS_TO, 'ScorecardItem', 'scorecard_item_id')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ballot_item_id' => 'Ballot Item',
            'scorecard_item_id' => 'Scorecard Item',
            'vote_id' => 'Vote',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('ballot_item_id', $this->ballot_item_id);
        $criteria->compare('scorecard_item_id', $this->scorecard_item_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}