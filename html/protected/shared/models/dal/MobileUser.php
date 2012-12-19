<?php

// Class for mobile_user document
class MobileUser extends ActiveMongoDocument {

    public $sessionTenantId;

    public function __construct($scenario = 'insert') {
        parent::__construct($scenario);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'mobile_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('device_identifier, device_type', 'required'),
            array('device_identifier, device_type', 'safe', 'on' => 'search'),
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
        
        if ($this->_id != "") {
            $criteria['_id'] =  new MongoId ( $this->_id ) ;
        }


        $cursor = MobileUser::model()->find($criteria); // $cursor acts as a Cdbcriteria

        return new EDMSDataProvider($cursor,
                        array(
                            'pagination' => array(
                                'pageSize' => 100,
                            )
                        )
        );
    }

}

