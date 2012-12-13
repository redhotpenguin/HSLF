<?php

/**
 * This is the model class for table "endorser_item".
 *
 * The followings are the available columns in table 'endorser_item':
 * @property integer $id
 * @property integer $endorser_id
 * @property integer $item_id
 * @property string $position
 *
 * The followings are the available model relations:
 * @property Item $item
 * @property Endorser $endorser
 */
class ItemEndorser extends CActiveRecord {

    public static $positions = array(
        'na' => 'N/A',
        'np' => 'No Position',
        'yes' => 'Yes',
        'no' => 'No',
    );

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ItemEndorser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'endorser_item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('endorser_id, item_id', 'required'),
            array('endorser_id, item_id', 'numerical', 'integerOnly' => true),
            array('position', 'length', 'max' => 32),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, endorser_id, item_id, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'Item' => array(self::BELONGS_TO, 'Item', 'item_id'),
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
            'item_id' => 'Item',
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
        $criteria->compare('item_id', $this->item_id);
        $criteria->compare('position', $this->position, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}