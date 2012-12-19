<?php

// Class for mobile_user document
class MobileUser extends ActiveMongoDocument {

    public $sessionTenantId;

    public function __construct($fields = array()) {
        parent::__construct("mobile_user");
        $this->fields = $fields;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            '_id' => 'ID'
        );
    }

    public function behaviors() {
        return array(
            'MultiTenant' => array(
                'class' => 'MultiTenantBehavior')
        );
    }

}

