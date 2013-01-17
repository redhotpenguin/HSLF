<?php

// Class for mobile_user document
class MobileUser extends ActiveMongoDocument {

    public $sessionTenantId;

    public function __construct($scenario = 'insert') {
        parent::__construct($scenario);

        $this->setAttributes(array('ua_identifier'));
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'mobile_user';
    }

    public function getAttributes($names = NULL) {
        return array(
        'tenant_id' => 'Tenant ID',
        'device_type' => 'Device Type',
        'ua_identifier' => 'Urban Airship Identifier',
        'districts' => 'Districts',
        'action_taken' => 'Action Taken',
        'tags' => 'Tags',
        'name' => 'Name',
        'email' => 'Email',
        'phone_number' => 'Phone Number',
        'registration_date' => 'Registration Date',
        'last_connection_date' => 'Last Connection date',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('device_type', 'required'),
            array('ua_identifier, device_type', 'safe', 'on' => 'search'),
            array('ua_identifier', 'uaIdentifierFormat')
        );
    }

    /**
     * Mobile Users behaviors
     */
    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {

        $criteria = array();

        if ($this->device_type != "") {
            $criteria['device_type'] = $this->device_type;
        }

        if ($this->ua_identifier != "") {
            $criteria['ua_identifier'] = $this->ua_identifier;
        }

        if ($this->tags != "") {
            $criteria['tags'] = $this->tags;
        }

        if ($this->districts != "") {
            $criteria['districts'] = $this->districts;
        }

        if ($this->_id != "") {
            $criteria['_id'] = new MongoId($this->_id);
        }

        $cursor = MobileUser::model()->find($criteria); // $cursor acts as a Cdbcriteria

        return new EDMSDataProvider($cursor,
                        array(
                            'pagination' => array(
                                'pageSize' => 50,
                            )
                        )
        );
    }

    /**
     * Validators - validate the Urban Airship Identifier format
     * Set a model error if the format is not correct
     */
    public function uaIdentifierFormat($attributes) {
        if ($this->ua_identifier == "") {
            return;
        }

        if ($this->device_type == 'ios') {
            // 64 hex characters ( only alpha and num)
            $pattern = "/^[a-zA-Z-0-9]{64}$/";
        } elseif ($this->device_type == 'android') {

            // XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX
            //     8hex 4hex 4hex 4hex 12 hex
            // 9fffae32-b3f5-4836-9078-e42e9f34f830
            $pattern = "/^[a-zA-Z-0-9]{8}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{4}-[a-zA-Z-0-9]{12}$/";
        } else {
            return;
        }

        if (preg_match($pattern, $this->ua_identifier) !== 1) {
            $this->addError('ua_identifier', 'Invalid UA Identifier');
        }
    }

}

