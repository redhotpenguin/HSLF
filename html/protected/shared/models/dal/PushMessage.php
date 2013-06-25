<?php

/**
 * This is the model class for table "push_message".
 *
 * The followings are the available columns in table 'push_message':
 * @property integer $id
 * @property integer $payload_id
 * @property string $creation_date
 * @property string $alert
 * @property string $push_identifier
 * @property string $recipient_type
 *
 * The followings are the available model relations:
 * @property Tag[] $tags
 * @property Payload $Payload
 */
class PushMessage extends BaseActiveRecord {

    public $delivered; // virtual property

    public function __construct($scenario = 'insert', $table = "") {
        $this->parentName = "Payload";
        $this->parentRelationship = "payload";
        $this->parentRelationshipAttribute = "payload_id";


        parent::__construct($scenario);
    }

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
            array('alert, creation_date, payload_id, recipient_type', 'required'),
            array('payload_id', 'numerical', 'integerOnly' => true),
            array('alert', 'length', 'max' => 140),
            array('recipient_type', 'length', 'max' => 16),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, payload_id, creation_date, alert, push_identifier, delivered, recipient_type', 'safe', 'on' => 'search'),
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
            'payload' => array(self::BELONGS_TO, 'Payload', 'payload_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'payload_id' => 'Payload',
            'creation_date' => 'Creation Date',
            'alert' => 'Message',
            'push_identifier' => 'Push Identifier',
            'recipient_type' => 'Recipient Type'
        );
    }

    /**
     * Return whether or not a message was deliverd to the push provider (urban airship)
     * @return boolean
     */
    public function isDelivered() {
        return $this->push_identifier ? true : false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = new CDbCriteria;

        if ($this->creation_date) {
            // trim white spaces
            $this->creation_date = trim($this->creation_date);

            // check that the format is yyyy-mm-dd. also checks that the values are correct
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->creation_date)) {
                $this->creation_date = str_replace('/', '-', $this->creation_date);
                $criteria->compare('creation_date', '> ' . $this->creation_date . ' 00:00:00', false);
                $criteria->compare('creation_date', '< ' . $this->creation_date . ' 23:59:59', false, 'AND');
            }
        }

        if ($this->delivered && $this->delivered !== 'any') {

            if ($this->delivered === 'true') {
                $criteria->addCondition('push_identifier IS NOT NULL');
            } else {
                $criteria->addCondition('push_identifier IS NULL');
            }
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('payload_id', $this->payload_id);
        $criteria->compare('alert', $this->alert, true);
        $criteria->compare('recipient_type', $this->recipient_type, false);


        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => array(
                        'defaultOrder' => $this->getTableAlias(false, false) . '.creation_date DESC'
                    )
                ));
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array('class' => 'MultiTenantBehavior'),
            'TagRelation' => array(
                'class' => 'TagRelationBehavior',
                'joinTableName' => 'tag_push_message',
                'tagRelationName' => 'push_messages', // relation to this class, defined in Tags.
                'foreignKeyName' => 'push_message_id'
                ));
    }

    public static function getRecipientTypes() {
        return array(
            'broadcast' => 'Broadcast',
            'tag' => 'Tag',
            'single' => 'Single',
            'segment' => 'Segment',
        );
    }

    public function scopes() {

        return array(
            'lastRecord' => array(
                'order' => $this->tableAlias . '.id DESC',
                'limit' => 1,
            ),
        );
    }

}