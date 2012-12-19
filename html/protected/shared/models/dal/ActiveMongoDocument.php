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
    private $collectionName = "mobile_user";
    private $collection;
    private static $model;
    public $searchAttributes = array(); // search attributes

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

        if (!$this->validate()) {
            $this->lastErrorCode = 0;
            $this->lastError = "a required field is missing";
            return false;
        }


        $this->beforeSave();

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
     * Use MongoDBCollection::findAndModify
     * @param array $condition - conditions used to find the document - optionnal
     * @param array $set  - fields to update -  optionnal
     * @param array $push - fields to append -  optionnal
     * @return mixed true for success - false is record didnt't get updated. -1 if record doesn't exist
     */
    public function update(array $conditions = array(), array $set = array(), array $push = array()) {

        $this->beforeSave();

        if (empty($conditions)) {
            $conditions = array('_id' => new MongoId($this->_id));
        }

        if (empty($conditions)) {
            throw new InvalidArgumentException("Conditions are missing");
        }

        if (!empty($push) || !empty($set)) {
            if (!empty($push))
                $data['$push'] = $push;

            if (!empty($set))
                $data['$set'] = $set;
        } else {
            $data = $this->fields;
        }

        try {
            $result = $this->collection->findAndModify($conditions, $data, null, array('new' => true));
        } catch (Exception $e) {
            $code = $e->getCode();

            // Mongodb Driver bug fix
            if ($code === 0) {
                return true;
            }
            $this->lastError = $e->getMessage();
            $this->lastErrorCode = $e->getCode();
            return false;
        }

        if (isset($result['_id'])) {
            $this->fields = $result;
            return true;
        } else {
            return -1;
        }
    }

    /**
     * Delete at most one document representing the instance of this class
     * @param integer acknoledgement level
     */
    public function delete($ackLevel = 1) {
        $result = $this->collection->remove(array('_id' => new MongoId($this->_id)), array('w' => $ackLevel, 'justOne' => true));

        return $this->checkResult($result);
    }

    /**
     * Find a document by ObjectId
     * @param string object id
     */
    public function findByPk($oid) {
        $this->beforeFind();

        $this->fields = $this->collection->findOne(array_merge(array('_id' => new MongoId($oid)), $this->searchAttributes));

        if (empty($this->fields))
            return null;

        return $this;
    }

    /**
     * Return a cursor
     */
    public function find() {
        $this->beforeFind();

        return $this->collection->find($this->searchAttributes);
    }

    /**
     * Find a document that match the attributes and values
     * @param array array of attribute and values
     */
    public function findByAttributes(array $attributes) {
        $this->beforeFind();

        $this->fields = $this->collection->findOne(array_merge($attributes, $this->searchAttributes));

        if (empty($this->fields))
            return null;

        return $this;
    }

    /**
     * Find all documents that match the attributes and values
     * @param array array of attribute and values
     */
    public function findAllByAttributes(array $attributes) {
        $result = array();
        $this->beforeFind();

        $resultSet = $this->collection->find(array_merge($attributes, $this->searchAttributes));

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

    /* Copied (and modified) from CActiveRecord.php */

    /**
     * Checks whether this Document has the named attribute
     * @param string $name attribute name
     * @return boolean whether this AR has the named attribute (table column).
     */
    public function hasAttribute($name) {
        return false;
    }

    /**
     * This method is invoked before an AR finder executes a find call.
     * The find calls include {@link find}, {@link findAll}, {@link findByPk},
     * {@link findAllByPk}, {@link findByAttributes} and {@link findAllByAttributes}.
     * The default implementation raises the {@link onBeforeFind} event.
     * If you override this method, make sure you call the parent implementation
     * so that the event is raised properly.
     */
    protected function beforeFind() {
        if ($this->hasEventHandler('onBeforeFind')) {
            $event = new CModelEvent($this);
            $this->onBeforeFind($event);
        }
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     * The default implementation raises the {@link onBeforeSave} event.
     * You may override this method to do any preparation work for record saving.
     * Use {@link isNewRecord} to determine whether the saving is
     * for inserting or updating record.
     * Make sure you call the parent implementation so that the event is raised properly.
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave() {
        if ($this->hasEventHandler('onBeforeSave')) {
            $event = new CModelEvent($this);
            $this->onBeforeSave($event);
            return $event->isValid;
        }
        else
            return true;
    }

    /**
     * This event is raised before the record is saved.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link save()} process will be stopped.
     * @param CModelEvent $event the event parameter
     */
    public function onBeforeSave($event) {
        $this->raiseEvent('onBeforeSave', $event);
    }

    /**
     * This event is raised after the record is saved.
     * @param CEvent $event the event parameter
     */
    public function onAfterSave($event) {
        $this->raiseEvent('onAfterSave', $event);
    }

    /**
     * This event is raised before the record is deleted.
     * By setting {@link CModelEvent::isValid} to be false, the normal {@link delete()} process will be stopped.
     * @param CModelEvent $event the event parameter
     */
    public function onBeforeDelete($event) {
        $this->raiseEvent('onBeforeDelete', $event);
    }

    /**
     * This event is raised after the record is deleted.
     * @param CEvent $event the event parameter
     */
    public function onAfterDelete($event) {
        $this->raiseEvent('onAfterDelete', $event);
    }

    /**
     * This event is raised before an AR finder performs a find call.
     * In this event, the {@link CModelEvent::criteria} property contains the query criteria
     * passed as parameters to those find methods. If you want to access
     * the query criteria specified in scopes, please use {@link getDbCriteria()}.
     * You can modify either criteria to customize them based on needs.
     * @param CModelEvent $event the event parameter
     * @see beforeFind
     */
    public function onBeforeFind($event) {
        $this->raiseEvent('onBeforeFind', $event);
    }

    /**
     * This event is raised after the record is instantiated by a find method.
     * @param CEvent $event the event parameter
     */
    public function onAfterFind($event) {
        $this->raiseEvent('onAfterFind', $event);
    }

}

