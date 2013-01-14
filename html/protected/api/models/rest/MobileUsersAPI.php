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
            return $this->buildErrorResponse(RestFailure::HTTP_BAD_REQUEST_CODE, self::ERROR_INCORRECT_USAGE_MSG);

        $userData = CJSON::decode($arguments['user'], false); // map json string to an stdobject

        if (!is_object($userData))
            return $this->buildErrorResponse(RestFailure::HTTP_BAD_REQUEST_CODE, self::ERROR_INCORRECT_DATA_MSG);


        $currentDate = new MongoDate();
        $mUser = new MobileUser();

        // save everything
        foreach ($userData as $key => $value) {
            $mUser->$key = $value;
        }

        $mUser->sessionTenantId = $tenantId;
        $mUser->registration_date = $currentDate;
        $mUser->last_connection_date = $currentDate;

        if ($mUser->save($this->ackLevel)) { // user saved successfully
            return array('id' => $mUser->_id->{'$id'});
        }
        
        if ($mUser->lastErrorCode == 11000) { // duplicate key error. Should not happen unless unique constraints are set
            return $this->buildErrorResponse(RestFailure::HTTP_CONFLICT_CODE, self::ERROR_USER_ALREADY_EXISTS_MSG);
        } elseif (!empty( $mUser->lastError ) ) {
            return $this->buildErrorResponse(RestFailure::HTTP_CONFLICT_CODE, $mUser->lastError);
        }

        return $this->buildErrorResponse(RestFailure::HTTP_INTERNAL_ERROR_CODE, $mUser->lastError);
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
            return $this->buildErrorResponse(RestFailure::HTTP_BAD_REQUEST_CODE, self::ERROR_INCORRECT_USAGE_MSG);

        $mUser = new MobileUser();
        $tenantId = (int) $tenantId;
        $set = array();
        $push = array();

        $userData = CJSON::decode($arguments['user'], true); // decode json string as an array

        if (!is_array($userData))
            return $this->buildErrorResponse(RestFailure::HTTP_BAD_REQUEST_CODE, self::ERROR_INCORRECT_DATA_MSG);

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
            return $this->buildErrorResponse(RestFailure::HTTP_NOT_FOUND_CODE, self::ERROR_USER_NOT_FOUND_MSG);
        } else {
            return $this->buildErrorResponse(RestFailure::HTTP_INTERNAL_ERROR_CODE, $mUser->lastError);
        }
    }

    /**
     * Helper - generate a unifor error response
     * @param integer $httpCode -  http error code
     * @param string $reason - failure reason
     * @return array failure 
     */
    private function buildErrorResponse($httpCode, $reason) {
        return new RestFailure($httpCode, array('error' => $reason ));
    }

    /**
     * Enable authentification for this end point
     * @return boolean
     */
    public function requiresAuthentification() {
        return true;
    }

}
