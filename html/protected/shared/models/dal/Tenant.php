<?php

/**
 * This is the model class for table "tenant".
 *
 * The followings are the available columns in table 'tenant':
 * @property integer $id
 * @property string $name
 * @property string $display_name
 * @property string $creation_date
 * @property integer $tenant_id
 * @property string $email
 * @property string $api_key
 * @property string $api_secret
 * @property string $ua_dashboard_link
 * @property string $cicero_user
 * @property string $cicero_password
 * @property string $ua_api_key
 * @property string $ua_api_secret
 *
 */
class Tenant extends CActiveRecord {

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
        return 'tenant';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, display_name,  creation_date, web_app_url, email, api_key, api_secret, ua_dashboard_link,ua_api_key,ua_api_secret, cicero_user, cicero_password', 'required'),
            array('name', 'length', 'max' => 32),
            array('display_name', 'length', 'max' => 256),
            array('name', 'match', 'pattern' => '/^([a-z0-9_])+$/'),
            array('ua_dashboard_link', 'safe'),
            array('email', 'email'),
            array('web_app_url, ua_dashboard_link', 'url'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, display_name, creation_date, tenant_id, email, api_key, api_secret, ua_dashboard_link', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
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
            'display_name' => 'Display Name',
            'creation_date' => 'Creation Date',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('creation_date', $this->creation_date, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}