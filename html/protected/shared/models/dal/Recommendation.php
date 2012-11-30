<?php

/**
 * This is the model class for table "recommendation".
 *
 * The followings are the available columns in table 'recommendation':
 * @property integer $id
 * @property string $value
 * @property string $type
 *
 * The followings are the available model relations:
 * @property BallotItem[] $ballotItems
 * @property BallotItem[] $ballotItems1
 */
class Recommendation extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Recommendation the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'recommendation';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('value, type', 'required'),
            array('value, type', 'length', 'max' => 64),
            array('tenant_account_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, value, type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'ballotItems' => array(self::HAS_MANY, 'BallotItem', 'recommendation_id'),
            'ballotItems1' => array(self::HAS_MANY, 'BallotItem', 'election_result_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'value' => 'Value',
            'type' => 'Type',
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
        $criteria->compare('value', $this->value, true);
        $criteria->compare('type', $this->type, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function getTypeOptions() {
        return array(
            'neutral' => 'Neutral',
            'positive' => 'Positive',
            'negative' => 'Negative',
        );
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}