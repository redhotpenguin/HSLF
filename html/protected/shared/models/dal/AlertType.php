<?php

/**
 * This is the model class for table "alert_type".
 *
 * The followings are the available columns in table 'alert_type':
 * @property integer $id
 * @property string $display_name
 * @property integer $tag_id
 * @property string $category
 *
 * The followings are the available model relations:
 * @property Tag $tag
 */
class AlertType extends CActiveRecord {

    public $tag_name; // doesn't belong to table alert_type. This field purpose is to enable tag search in the admin view

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return AlertType the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'alert_type';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('display_name, category, tag_id','required'),
            array('tag_id', 'numerical', 'integerOnly' => true),
            array('display_name', 'length', 'max' => 1024),
            array('category', 'length', 'max' => 512),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, display_name, tag_id, tag_name, category', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'tag' => array(self::BELONGS_TO, 'Tag', 'tag_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'display_name' => 'Display Name',
            'tag_id' => 'Tag ID',
            'Tag' => 'Tag',
            'category' => 'Category',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.
        //error_log(print_r($_REQUEST, true));
        $criteria = new CDbCriteria;

        if ($this->tag_name != '') {
            $criteria->together = true;
            $criteria->with = array('tag');
            $criteria->compare('tag.name', $this->tag_name, false);
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('category', $this->category, true);
        $criteria->compare('tag_id', $this->tag_id);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}