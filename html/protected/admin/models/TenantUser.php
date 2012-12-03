<?php

/**
 * This is the model class for table "tenant_user".
 *
 * The followings are the available columns in table 'tenant_user':
 * @property integer $id
 * @property integer $tenant_id
 * @property integer $user_id
 * @property string $role
 *
 * The followings are the available model relations:
 * @property Tenant $tenant
 * @property User $user
 */
class TenantUser extends CActiveRecord {

    const ADMIN_ROLE = 'admin';
    const PUBLISHER_ROLE = 'publisher';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return TenantUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'tenant_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('tenant_id, user_id, role', 'required'),
            array('tenant_id, user_id', 'numerical', 'integerOnly' => true),
            array('role', 'length', 'max' => 128),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tenant_id, user_id, role', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'tenant' => array(self::BELONGS_TO, 'Tenant', 'tenant_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tenant_id' => 'Tenant Account',
            'user_id' => 'User',
            'role' => 'Role',
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
        $criteria->compare('tenant_id', $this->tenant_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('role', $this->role, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    // @todo: move to behavior
    public function hasUser($userID) {
        error_log("checking if tenant $this->id has : " . $userID);
    }

}