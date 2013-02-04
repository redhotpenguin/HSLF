<?php

/**
 * This is the model class for table "push_message".
 *
 * The followings are the available columns in table 'push_message':
 * @property integer $id
 * @property integer $tenant_id
 * @property integer $share_payload_id
 * @property string $creation_date
 * @property string $alert
 *
 * The followings are the available model relations:
 * @property Tag[] $tags
 * @property Tenant $tenant
 * @property SharePayload $sharePayload
 */
class PushMessage extends BaseActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PushMessage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'push_message';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('creation_date', 'required'),
            array('tenant_id, share_payload_id', 'numerical', 'integerOnly' => true),
            array('alert', 'length', 'max' => 140),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, tenant_id, share_payload_id, creation_date, alert', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'tags' => array(self::MANY_MANY, 'Tag', 'tag_push_message(push_message_id, tag_id)'),
            'share_payload' => array(self::BELONGS_TO, 'SharePayload', 'share_payload_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'tenant_id' => 'Tenant',
            'share_payload_id' => 'Share Payload',
            'creation_date' => 'Creation Date',
            'alert' => 'Alert',
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
        $criteria->compare('share_payload_id', $this->share_payload_id);
        $criteria->compare('creation_date', $this->creation_date, true);
        $criteria->compare('alert', $this->alert, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior'),
            'TagRelation' => array(
                'class' => 'TagRelationBehavior',
                'joinTableName' => 'tag_push_message',
                'tagRelationName' => 'push_messages', // relation to this class, defined in Tags.
                'foreignKeyName' => 'push_message_id'
            )
        );
    }

}