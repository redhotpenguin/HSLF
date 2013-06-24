<?php

/**
 * This is the model class for table "payload".
 *
 * The followings are the available columns in table 'payload':
 * @property integer $id
 * @property integer $tenant_id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $tweet
 * @property string $email
 * @property string $type
 * @property integer $postNumber
 *
 */
class Payload extends BaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Payload the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'payload';
    }

    public function getTypeOptions() {
        return array('other' => 'None', 'post' => 'Push to Post', 'share' => 'Push to Share');
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, type', 'required'),
            array('post_number, url, tweet', 'required', 'on' => 'composer'),
            array('post_number', 'required', 'on' => 'type_post'),
            array('url, tweet ', 'required', 'on' => 'type_share'),
            array('tenant_id, post_number', 'numerical', 'integerOnly' => true),
            array('url', 'length', 'max' => 2048),
            array('title', 'length', 'max' => 512),
            array('tweet', 'length', 'max' => 140),
            array('email', 'length', 'max' => 320),
            array('type', 'length', 'max' => 16),
            array('url', 'url'),
            array('email', 'email'),
            array('id, url, title, description, tweet, email', 'safe', 'on' => 'update,insert'),
            array('id, url, title, description, tweet, email', 'safe', 'on' => 'search'),
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
            'tenant_id' => 'Tenant',
            'url' => 'Url',
            'title' => 'Title',
            'description' => 'Description',
            'tweet' => 'Tweet',
            'email' => 'Email',
            'type' => 'Action Type',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {


        $criteria = new CDbCriteriaInsensitive();

        if (is_numeric($this->id))
            $criteria->compare('id', $this->id);



        $criteria->compare('url', $this->url, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('tweet', $this->tweet, true);
        $criteria->compare('email', $this->email, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /**
     * Attached behaviors
     */
    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}