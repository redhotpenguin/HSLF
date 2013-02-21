<?php

/**
 * This is the model class for table "contact".
 *
 * The followings are the available columns in table 'contact':
 * @property integer $id
 * @property integer $tenant_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $title
 * @property string $phone_number
 *
 * The followings are the available model relations:
 * @property Organization[] $organizations
 */
class Contact extends BaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Contact the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'contact';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tenant_id, first_name', 'required'),
            array('tenant_id', 'numerical', 'integerOnly' => true),
            array('email', 'length', 'max' => 128),
            array('title, phone_number', 'length', 'max' => 512),
            array('last_name', 'safe'),
            array('id, first_name, last_name, email, title, phone_number', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {

        return array(
            'organizations' => array(self::MANY_MANY, 'Organization', 'contact_organization(contact_id, organization_id)'),
        );
    }

    /*
     * Behaviors 
     */

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'title' => 'Title',
            'phone_number' => 'Phone Number',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('phone_number', $this->phone_number, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}