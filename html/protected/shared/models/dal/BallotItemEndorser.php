<?php

/**
 * This is the model class for table "endorser_ballot_item".
 *
 * The followings are the available columns in table 'endorser_ballot_item':
 * @property integer $id
 * @property integer $endorser_id
 * @property integer $ballot_item_id
 * @property string $position
 *
 * The followings are the available model relations:
 * @property BallotItem $ballotItem
 * @property Endorser $endorser
 */
class BallotItemEndorser extends CActiveRecord {

    public static $positions = array(
        'np' => 'No Position',
        'yes' => 'Yes',
        'no' => 'No',
        'split' => 'Split',
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return BallotItemEndorser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'endorser_ballot_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('endorser_id, ballot_item_id', 'required'),
            array('endorser_id, ballot_item_id', 'numerical', 'integerOnly' => true),
            array('position', 'length', 'max' => 32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, endorser_id, ballot_item_id, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ballotItem' => array(self::BELONGS_TO, 'BallotItem', 'ballot_item_id'),
            'endorser' => array(self::BELONGS_TO, 'Endorser', 'endorser_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'endorser_id' => 'Endorser',
            'ballot_item_id' => 'Ballot Item',
            'position' => 'Position',
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
        $criteria->compare('endorser_id', $this->endorser_id);
        $criteria->compare('ballot_item_id', $this->ballot_item_id);
        $criteria->compare('position', $this->position, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}