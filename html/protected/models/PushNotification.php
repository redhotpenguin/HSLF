<?php

/**
 * This is the model class for table "push_notification".
 *
 * The followings are the available columns in table 'push_notification':
 * @property integer $id
 * @property string $message
 * @property string $sent
 * @property string $create_time
 */
class PushNotification extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PushNotification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'push_notification';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('message', 'required'),
            array('id, message, sent, create_time', 'safe', 'on' => 'search'),
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
            'message' => 'Message',
            'sent' => 'Sent',
            'create_time' => 'Create Time',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('message', $this->message, true);
        $criteria->compare('sent', $this->sent, true);
        $criteria->compare('create_time', $this->create_time, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array('pageSize' => 50),
                ));
    }

}