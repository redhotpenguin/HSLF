<?php

/**
 * This is the model class for table "tenant_setting".
 *
 * The followings are the available columns in table 'tenant_setting':
 * @property integer $id
 * @property string analytics_link
 * @property string ios_link
 * @property string android_link
 *
 */
class TenantSetting extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Tenantthe static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenant_setting';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('analytics_link,ios_link,android_link', 'required'),
            array('analytics_link,ios_link,android_link', 'url'),
            array('id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'analytics_link' => 'Analytics Link',
            'ios_link' => 'iOs Store Link',
            'android_link' => 'Android Store Link',
        );
    }

}

