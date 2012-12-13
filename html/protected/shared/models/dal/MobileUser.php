<?php

// Experimental class  - uses MongoDb for storage (no schema!)
// represent mobile_user document
// @todo: massive unit testing!
class MobileUser extends CModel {

    private $collectionName = "mobile_user";
    public $tenantId;
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
     //   if (isset($this->fields[$attribute]))
            $this->fields[$attribute] = $value;
      //  else
       //     array_push($this->fields, array($attribute => $value));
    }

    public function __construct($fields = array()) {
        $this->attachBehaviors($this->behaviors());
        $this->afterConstruct();
        $this->fields = $fields;

        if (self::$mongoClient == null)
            self::$mongoClient = Yii::app()->edmsMongo();

        $this->collection = Yii::app()->edmsMongoCollection($this->collectionName);
    }

    public function attributeNames() {
        return array();
    }

    public static function model($className = __CLASS__) {
        if (self::$model == null)
            self::$model = new MobileUser();

        return self::$model;
    }

    /**
     * Add the current instance to the mobile_user collection
     * @return boolean
     */
    public function save($writeConcern = 1) {
        if(!$this->tenantId){
            return false;
        }
        $this->fields['tenant_id'] = $this->tenantId;
        
        try {
            $result = $this->collection->insert($this->fields, array($writeConcern));
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            $this->lastErrorCode = $e->getCode();
            $result = false;
        }

        return $result;
    }

    public function update() {
        logIt($this->fields);
        
      $test =   $this->collection->update( array('_id' => new MongoId($this->_id ) ), $this->fields);
      error_log(logIt($test));
    }
    
    public function delete(){
        return $this->collection->remove( array('_id' => new MongoId($this->_id )));
    }

    public function findByPk($oid) {
        $this->fields = $this->collection->findOne(array('_id' => new MongoId($oid)));
        return $this;
    }

    public function findByAttributes(array $attributes) {
        $this->fields = $this->collection->findOne($attributes);
        return $this;
    }

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
