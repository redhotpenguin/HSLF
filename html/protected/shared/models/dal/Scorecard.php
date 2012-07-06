<?php

/**
 * This is the model class for table "scorecard".
 *
 * The followings are the available columns in table 'scorecard':
 * @property integer $id
 * @property integer $ballot_item_id
 * @property string $type
 * @property string $name
 * @property string $vote
 */
class Scorecard extends CActiveRecord {

    private $types = array(
        'house' => 'house',
        'senate' => 'senate',
    );
    private $names = array(
        'Puppy Mills Cosponsor' => 'Puppy Mills Cosponsor',
        'Chimps in Labs Cosponsor' => 'Chimps in Labs Cosponsor',
        'Horse Slaughter Cosponsor' => 'Horse Slaughter Cosponsor',
        'Ag Subsidies Vote' => 'Ag Subsidies Vote',
        'Leaders' => 'Leaders',
        'Lethal Predator Control Vote' => 'Lethal Predator Control Vote',
        'ESA Vote' => 'ESA Vote',
        'Funding Letter' => 'Funding Letter',
        'Animal Fighting Cosponsor' => 'Animal Fighting Cosponsor',
    );
    private $votes = array(
        'Y' => 'Y',
        'N' => 'N',
        'SP' => 'SP',
        'NV' => 'NV',
        'N/A' => 'N/A',
    );

    public function getTypeOptions() {
        return $this->types;
    }

    public function getNameOptions() {
        return $this->names;
    }

    public function getVoteOptions() {
        return $this->votes;
    }

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
            array('ballot_item_id', 'required'),
            array('ballot_item_id', 'numerical', 'integerOnly' => true),
            array('type', 'length', 'max' => 128),
            array('name', 'length', 'max' => 256),
            array('vote', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, ballot_item_id, type, name, vote', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'ballot_item_id' => 'Ballot Item',
            'type' => 'Type',
            'name' => 'Name',
            'vote' => 'Vote',
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
        $criteria->compare('type', $this->type, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('vote', $this->vote, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}