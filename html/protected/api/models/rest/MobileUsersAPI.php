<?php

/**
 * Description of MobileUser
 *
 * @author jonas
 */
class MobileUsersAPI implements IAPI {

    const ERROR_USER_NOT_FOUND_MSG = "user not found";
    const ERROR_USER_ALREADY_EXISTS_MSG = "user already exists";
    const ERROR_INCORRECT_USAGE_MSG = "incorrect usage";
    const ERROR_INCORRECT_DATA_MSG = "incorrect data";
    const SUCCESS_MSG = "success";

    private $ackLevel;

    public function __construct() {
        $this->ackLevel = (Yii::app()->params['mongodb_ack_level']) ? Yii::app()->params['mongodb_ack_level'] : 1;
    }

    /**
     * Get a list of  mobile users
     * @param integer $tenantId - tenant id
     * @param array $arguments - options
     * @return array array of MobileUser
     */
    public function getList($tenantId, $arguments = array()) {
        return "not supported";
    }

    /**
     * Get a single mobile user
     * @param integer $tenantId - tenant id
     * @param integer $deviceIdentifier - device identifier
     * @param array $arguments - options
     * @return MobileUser mobile user
     */
    public function getSingle($tenantId, $deviceIdentifier, $arguments = array()) {
        return "not supported";
    }

    /**
     * Add a  MobileUser
     * @param integer $tenantId - tenant id
     * @param array $arguments - options
     * @return mixed success or failure (array)
     */
    public function create($tenantId, $arguments = array()) {

        if (!isset($arguments['user']))
            throw new RestException(400, self::ERROR_INCORRECT_USAGE_MSG);

        $tenantId = (int) $tenantId;

        $userData = CJSON::decode($arguments['user'], false); // map json string to an stdobject

        if (!is_object($userData))
            throw new RestException(400, self::ERROR_INCORRECT_DATA_MSG);

        if (isset($userData->ua_identifier)) {

            $mUser = MobileUser::model()->findByAttributes(
                    array(
                        "tenant_id" => 1,
                        "ua_identifier" => $userData->ua_identifier
                    ));

            if ($mUser != null && $mUser->ua_identifier == $userData->ua_identifier) {
                throw new RestException(409, "ua identifier already used");
            }
        }

        $currentDate = new MongoDate();
        $mUser = new MobileUser();

        // save everything
        foreach ($userData as $key => $value) {
            $mUser->$key = $value;
        }

        $mUser->registration_date = $currentDate;
        $mUser->last_connection_date = $currentDate;

        if ($mUser->save($this->ackLevel)) { // user saved successfully
            return array('id' => $mUser->_id->{'$id'});
        }

        if ($mUser->lastErrorCode == 11000) { // duplicate key error. Should not happen unless unique constraints are set
            throw new RestException(409, self::ERROR_USER_ALREADY_EXISTS_MSG);
        } elseif (!empty($mUser->lastError)) {
            throw new RestException(409,  $mUser->lastError[0] );
        }

        throw new RestException(500, $mUser->lastError[0] );
    }

    /**
     * Update an existing MobileUser
     * @param integer $tenantId - tenant id
     * @param integer $id - object identifier
     * @param array $arguments - options
     * @return mixed success or failure (array)
     */
    public function update($tenantId, $id, $arguments = array()) {

        if (!isset($arguments['user']))
            throw new RestException(400, self::ERROR_INCORRECT_USAGE_MSG);

        $mUser = new MobileUser();
        $tenantId = (int) $tenantId;
        $set = array();
        $push = array();

        $userData = CJSON::decode($arguments['user'], true); // decode json string as an array

        if (!is_array($userData))
            throw new RestException(400, self::ERROR_INCORRECT_DATA_MSG);

        $conditions = array(
            "_id" => new MongoId($id)
        );

        if (isset($userData['set'])) {
            $set = $userData['set'];
        }
        if (isset($userData['push'])) {
            $push = $userData['push'];
        }

        $set['last_connection_date'] = new MongoDate();

        $updateResult = $mUser->update($conditions, $set, $push);


        if ($updateResult === true) {
            return self::SUCCESS_MSG;
        } elseif ($updateResult === -1) {
            throw new RestException(404, self::ERROR_USER_NOT_FOUND_MSG);
        } else {
            throw new RestException(500, $mUser->lastError[0] );
        }
    }


    /**
     * Enable authentification for this end point
     * @return boolean
     */
    public function requiresAuthentification() {
        return true;
    }

    public function getCacheDuration() {
        return 0;
    }

}
