<?php

/**
 * Base Data Access Layer for MongoDB
 *
 * @author jonas
 */
class ActiveMongoDocument extends CModel {

    public $fields;
    public $lastErrorCode;
    public static $mongoClient;
    public $lastError;
    public $sessionTenantId;
    private $collectionName = "mobile_user";
    private $collection;
    private static $model;

    /**
     * Constructor
     * @param array $fields fields of the document
     */
    public function __construct($collectionName) {
        $this->attachBehaviors($this->behaviors());
        $this->afterConstruct();

        if (self::$mongoClient == null)
            self::$mongoClient = Yii::app()->edmsMongo();

        $this->collection = Yii::app()->edmsMongoCollection($this->collectionName);
    }

    /**
     * Magic getter
     */
    public function __get($attribute) {
        if (isset($this->fields[$attribute]))
            return $this->fields[$attribute];
    }

    /**
     * Magic setter
     */
    public function __set($attribute, $value) {
        $this->fields[$attribute] = $value;
    }

    /**
     * Return attributes of this class
     */
    public function attributeNames() {
        return array();
    }

    /**
     * Return an instance of this class
     */
    public static function model($className = __CLASS__) {
        if (self::$model == null)
            self::$model = new MobileUser();

        return self::$model;
    }

    /**
     * Add the current instance to the mobile_user collection
     * @param integer acknoledgement level
     * @return boolean
     */
    public function save($ackLevel = 1) {
        if (!isset($this->fields['tenant_id'])) {
            return false;
        }

        try {
            $result = $this->collection->insert($this->fields, array('w' => $ackLevel));
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            $this->lastErrorCode = $e->getCode();
            $result = false;
        }

        return $this->checkResult($result);
    }

    /**
     * Save the changes made to the instance of this class
     * @param integer acknoledgement level
     */
    public function update($ackLevel = 1) {
        if (!isset($this->fields['tenant_id'])) {
            return false;
        }
        $result = $this->collection->update(array('_id' => new MongoId($this->_id)), $this->fields, array('w' => $ackLevel));

        return $this->checkResult($result);
    }

    /**
     * Delete at most one document representing the instance of this class
     * @param integer acknoledgement level
     * @todo: enforce tenant compliancy
     */
    public function delete($ackLevel = 1) {
        $result = $this->collection->remove(array('_id' => new MongoId($this->_id)), array('w' => $ackLevel, 'justOne' => true));

        return $this->checkResult($result);
    }

    /**
     * Find a document by ObjectId
     * @param string object id
     * @todo: enforce tenant compliancy
     */
    public function findByPk($oid) {
        $this->fields = $this->collection->findOne(array('_id' => new MongoId($oid)));

        if (empty($this->fields))
            return null;

        return $this;
    }

    /**
     * Find a document that match the attributes and values
     * @param array array of attribute and values
     */
    public function findByAttributes(array $attributes) {
        if (isset($this->sessionTenantId))
            $attributes['tenant_id'] = $this->sessionTenantId;

        $this->fields = $this->collection->findOne($attributes);

        if (empty($this->fields))
            return null;

        return $this;
    }

    /**
     * Find all documents that match the attributes and values
     * @param array array of attribute and values
     * @todo: add unit test to check tenant compliancy
     */
    public function findAllByAttributes(array $attributes) {
        $result = array();

        if (isset($this->sessionTenantId))
            $attributes['tenant_id'] = $this->sessionTenantId;

        $resultSet = $this->collection->find($attributes);

        foreach ($resultSet as $document) {
            array_push($result, new MobileUser($document));
        }

        return $result;
    }

    /**
     * Helper function: check the result of an operation
     * @param arary $result result returned by a write/update/delete operation
     * return boolean operation result
     */
    private function checkResult($result) {
        if (isset($result['ok']) && $result['ok'] == 1) {
            return true;
        }

        return false;
    }

}

