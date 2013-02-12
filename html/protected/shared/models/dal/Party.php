<?php

/**
 * This is the model class for table "party".
 *
 * The followings are the available columns in table 'party':
 * @property integer $id
 * @property string $name
 * @property string $abbr
 * @property string $initial
 */
class Party extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Party the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'party';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, abbr', 'required'),
            array('name', 'length', 'max' => 2048),
            array('abbr', 'length', 'max' => 128),
            array('initial', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, abbr, initial', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'abbr' => 'Abbreviation',
            'initial' => 'Initial',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteriaInsensitive();

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('abbr', $this->abbr, true);
        $criteria->compare('initial', $this->initial, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}