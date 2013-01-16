<?php

/**
 * This is the model class for table "share_payload".
 *
 * The followings are the available columns in table 'share_payload':
 * @property integer $id
 * @property integer $tenant_id
 * @property string $url
 * @property string $title
 * @property string $description
 * @property string $tweet
 * @property string $email
 *
 * The followings are the available model relations:
 * @property Tenant $tenant
 */
class SharePayload extends CBaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return SharePayload the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'share_payload';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('url, title, description, tweet, email', 'required'),
            array('tenant_id', 'numerical', 'integerOnly' => true),
            array('url', 'length', 'max' => 2048),
            array('title', 'length', 'max' => 512),
            array('tweet', 'length', 'max' => 140),
            array('email', 'length', 'max' => 320),
            array('url', 'url'),
            array('email', 'email'),
            array('id, tenant_id, url, title, description, tweet, email', 'safe', 'on' => 'search'),
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