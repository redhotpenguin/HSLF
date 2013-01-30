<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 */
class User extends CBaseActiveRecord {

    const ADMIN_ROLE = 'admin';
    const PUBLISHER_ROLE = 'publisher';

    public $repeat_password;
    public $initial_password;
    public $role;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('username,email', 'required', 'on' => 'update'),
            array('password, username, email, repeat_password', 'required', 'on' => 'insert'),
            array('repeat_password', 'compare', 'compareAttribute' => 'password', 'on' => 'insert'),
            array('email', 'email'),
            array('username, email', 'length', 'max' => 128),
            array('password', 'length', 'max' => 40),
            array('role', 'safe'),
            array('id, username, email', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'tenants' => array(self::MANY_MANY, 'Tenant',
                'tenant_user(user_id,tenant_id)')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
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
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function beforeSave() {

        // a new record is added
        if ($this->isNewRecord) {

            $this->password = get_hash($this->password);
        }
        // an existing record is updated
        else {

            // a new password is given
            if ($this->password) {
                $password = get_hash($this->password);
            }

            // no password given, restore the initial pass
            else
                $password = $this->initial_password;

            $this->password = $password;
        }

        return parent::beforeSave();
    }

    /**
     *   Save a User model
     */
    public function save($runValidation = true, $attributes = null) {
        $save_result = false;

        try {
            $save_result = parent::save();
        } catch (CDbException $cdbe) {
            error_log("error");

            switch ($cdbe->getCode()) {
                case 23505:
                    $this->addError("", 'A user with this username or with this email already exists.');
                    break;

                default: // we can't handle the error, rethrow it!
                    throw $cdbe;
            }
        }

        return $save_result;
    }

    public function behaviors() {
        return array(
            'UserRbacBehavior' => array('class' => 'UserRbacBehavior'),
        );
    }

    /**
     * Retrieves a list of possible user roles.
     * @return array of roles
     */
    public function getRoleOptions() {
        return array(
            self::ADMIN_ROLE => 'Administrator',
            self::PUBLISHER_ROLE => 'Publisher',
        );
    }

}