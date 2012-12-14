<?php

// Experimental class  - uses MongoDb for storage (no schema!)
// represent mobile_user document
// @todo: massive unit testing!
class MobileUser extends CModel {

    private $collectionName = "mobile_user";
    public $fields;
    private $collection;
    public static $mongoClient;
    public $lastError;
    public $lastErrorCode;
    private static $model;

    public function __get($attribute) {
        if (isset($this->fields[$attribute]))
            return $this->fields[$attribute];
    }

    public function __set($attribute, $value) {
        $this->fields[$attribute] = $value;
    }

    /**
     * Constructor
     * @param array $fields fields of the document
     */
    public function __construct($fields = array()) {
        $this->attachBehaviors($this->behaviors());
        $this->afterConstruct();
        $this->fields = $fields;

        if (self::$mongoClient == null)
            self::$mongoClient = Yii::app()->edmsMongo();

        $this->collection = Yii::app()->edmsMongoCollection($this->collectionName);
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

        return $result;
    }

    /**
     * Save the changes made to the instance of this class
     * @param integer acknoledgement level
     * @todo: validation
     */
    public function update($ackLevel) {
        if (!isset($this->fields['tenant_id'])) {
            return false;
        }
        $this->collection->update(array('_id' => new MongoId($this->_id)), $this->fields, array('w' => $ackLevel));
    }

    /**
     * Delete at most one document representing the instance of this class
     * @param integer acknoledgement level
     * @todo: validation
     * @todo: enforce tenant compliancy
     */
    public function delete() {
        return $this->collection->remove(array('_id' => new MongoId($this->_id)), array('w' => $ackLevel, 'justOne'=> true));
    }

    /**
     * Find a document by ObjectId
     * @param string object id
     * @todo: validation
     * @todo: enforce tenant compliancy
     */
    public function findByPk($oid) {
        $this->fields = $this->collection->findOne(array('_id' => new MongoId($oid)));
        return $this;
    }

    /**
     * Find a document that match the attributes and values
     * @param array array of attribute and values
     * @todo: validation
     * @todo: enforce tenant compliancy
     */
    public function findByAttributes(array $attributes) {
        $this->fields = $this->collection->findOne($attributes);
        return $this;
    }

    /**
     * Find all documents that match the attributes and values
     * @param array array of attribute and values
     * @todo: validation
     * @todo: enforce tenant compliancy
     */
    public function findAllByAttributes(array $attributes) {
        $result = array();

        $resultSet = $this->collection->find($attributes);

        foreach ($resultSet as $document) {
            array_push($result, new MobileUser($document));
        }

        return $result;
    }

}

?>
