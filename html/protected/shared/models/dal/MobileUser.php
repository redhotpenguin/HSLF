<?php

// Class for mobile_user document
class MobileUser extends ActiveMongoDocument {

    public $sessionTenantId;

    public function __construct($fields = array()) {
        parent::__construct("mobile_user");
        $this->fields = $fields;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('device_identifier, device_type', 'required')
        );
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}

