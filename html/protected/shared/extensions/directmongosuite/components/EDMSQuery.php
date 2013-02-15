<?php

/**
 * EDMSQuery.php
 *
 * A component to simplify the find methods and result handling of the mongoDB
 *
 *
 * PHP version 5.2+
 *
 * @author Joe Blocher <yii@myticket.at>
 * @copyright 2011 myticket it-solutions gmbh
 * @license New BSD License
 * @category Database
 * @package directmongosuite
 * @version 0.2.5
 * @since 0.1
 */
class EDMSQuery
{
	const SORT_ASC = 1;
	const SORT_DESC = -1;

    const CALLAFTER_MONGODBCREATE = 'callAfterMongoDbCreate';
    const CALLAFTER_COLLECTIONCREATE = 'callAfterCollectionCreate';
    const CALLAFTER_CURSORCREATE = 'callAfterCursorCreate';
    const CALLAFTER_COLLECTIONFIND = 'callAfterCollectionFind';

	private $_collectionName;
	private $_connectionId;
	private $_dbName;

    private $_callAfterMongoDbCreate;
    private $_callAfterCollectionCreate;
    private $_callAfterCursorCreate;
    private $_callAfterCollectionFind;

    private $_callResults;


	protected static $_instance;

	private $_collection;
	private $_cursor;
	private $_db;


	/**
	 * Create a EDMSQuery for the specified collection
	 * Uses the preconfigured connection from EDMSBehavior if dbName, $connectionId is not set
	 *
	 * @param string $collectionName
	 * @param string $dbName
	 * @param string $connectionId
	 */
	public function __construct($collectionName,$dbName=null,$connectionId=null)
	{
	   	$this->_collectionName = $collectionName;
	   	$this->_dbName = $dbName;
	   	$this->_connectionId = $connectionId;
        $this->_callResults = array();
	}

	/**
	 * EDMSQuery::instance()
	 *
	 * @param mixed $collectionName
	 * @return
	 */
	public static function instance($collectionName)
	{
		if (!isset(self::$_instance))
			self::$_instance = array();

		if (!isset(self::$_instance[$collectionName]))
		  self::$_instance[$collectionName] = new EDMSQuery($collectionName);

		return self::$_instance[$collectionName];
	}


    /**
     * Assign the array(method1=>array($arg1,$arg2,...),method2=>array($arg1,$arg2,...) ...)
     * called after creating the MongoDb instance
     *
     * @param array $methods
     * @return EDMSQuery
     * @since 0.25
     */
    public function setCallAfterMongoDbCreate($methods)
    {
      $this->_callAfterMongoDbCreate = $methods;
      return $this;
    }

    /**
     * Assign the array(method1=>array($arg1,$arg2,...),method2=>array($arg1,$arg2,...) ...)
     * called after creating the MongoCollection instance
     *
     * @param array $methods
     * @return EDMSQuery
     * @since 0.25
     */
    public function setCallAfterCollectionCreate($methods)
    {
        $this->_callAfterCollectionCreate = $methods;
        return $this;
    }

    /**
     * Assign the array(method1=>array($arg1,$arg2,...),method2=>array($arg1,$arg2,...) ...)
     * called after calling the MongoCollection->find
     *
     * @param array $methods
     * @return EDMSQuery
     * @since 0.25
     */
    public function setCallAfterCollectionFind($methods)
    {
        $this->_callAfterCollectionFind = $methods;
        return $this;
    }

    /**
     * Assign the array(method1=>array($arg1,$arg2,...),method2=>array($arg1,$arg2,...) ...)
     * called after creating the MongoCollection instance
     *
     * Example: EDMSQuery::instance($collectionName)->setCallAfterCursorCreate(array('limit'=>1,'sort'=>array('weight'=>1,'title'=>-1)))->findArray($criteria,$select);
     *
     * @param array $methods
     * @return EDMSQuery
     * @since 0.25
     */
    public function setCallAfterCursorCreate($methods)
    {
        $this->_callAfterCursorCreate = $methods;
        return $this;
    }


    /**
     * Call the methods of an object
     * @param $object
     * @param $methods array('methodName'=>array($arg1,$arg2,...))
     * @since 0.25
     */
    protected function callObjectMethods($object,$methods,$resultKey)
    {
        if(!empty($object) && !empty($methods))
        {
           $this->_callResults[$resultKey] = array();

           foreach ($methods as $method => $args)
           {
               if(method_exists($object,$method))
                   $this->_callResults[$resultKey][$method] = call_user_func_array(array($object,$method), array($args));
               else
                   throw new CException('Method not exists');
           }
        }
    }


    /**
     * Reset the result of the call_user_func_array methods from $this->callObjectMethods
     * @since 0.25
     */
    public function resetCallResults()
    {
        unset($this->_callResults);
        $this->_callResults = array();
    }

    /**
     * Get the results of the  call_user_func_array methods from $this->callObjectMethods
     *
     * @param null $type
     * @param null $method
     * @return array|null
     * @since 0.25
     */
    public function getCallResults($type=null,$method=null)
    {
        if(isset($$type))
        {
           if(isset($this->_callResults[$type]))
           {
              if(isset($method))
                  return isset($this->_callResults[$type][$method]) ? $this->_callResults[$type][$method] : null;
              else
                  return $this->_callResults[$type];

           }
           else return null;
        }

        return $this->_callResults;
    }

	/**
	 * Get an instance of the PHP MongoDB class
	 *
	 * @return edmsMongo
	 */
	public function getDb()
	{
		if (!isset($this->_db))
		  $this->_db = Yii::app()->edmsMongoDb($this->_dbName,$this->_connectionId);

        $this->callObjectMethods($this->_db,$this->_callAfterMongoDbCreate,self::CALLAFTER_MONGODBCREATE);

		return $this->_db;
	}


	/**
	 * Get an instance of the PHP MongoCollection class
	 *
	 * @return edmsMongoCollection
	 */
	public function getCollection()
	{
		if (!isset($this->_collection))
        {
            $this->_collection = Yii::app()->edmsMongoCollection($this->_collectionName,$this->_dbName,$this->_connectionId);
            $this->callObjectMethods($this->_collection,$this->_callAfterCollectionCreate,self::CALLAFTER_COLLECTIONCREATE);
        }

		return $this->_collection;
	}


    /**
     * Returns the current cursor generated by the method findCursor
     * @return object MongoCursor
     * @since 0.25
     */
    public function getCursor()
    {
      return $this->_cursor;
    }


	/**
	 * Generate the criteria for searching for keywords
	 * Set the operator AND (default) or OR when there are more keywords in the string
	 *
	 * Usage:
	 * $criteria = EDMSQuery::getWordsSearchCriteria('example for',array('title','subtitle'));
	 * will return the criteria to search for title or subtitle containing 'example' AND 'for'
	 *
	 * @param string $keywords
	 * @param array $searchAttributes
	 * @param boolean $orMode
	 * @return array
	 */
	public static function getWordsSearchCriteria($keywords,$searchAttributes,$orMode = false)
	{
		$criteria = array();

		if (!empty($keywords))
		{
			// split words
			$words = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[\s,]+/",
			    $keywords, 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

			if (!empty($words))
			{
				$orArray = array();

				if ($orMode) //combine each word as OR
				{
						foreach ($words as $word)
						{
							// regex for containing
							$regexObj = new MongoRegex('/' . $word . '/i');

							foreach ($searchAttributes as $attribute)
								$orArray[] = array($attribute => $regexObj);
						}
				}
				else //combine as AND
				{
					$andArray = array();
					foreach ($searchAttributes as $attribute)
					{
						$andArray = array();
						foreach ($words as $word)
						{
							$regexObj = new MongoRegex('/'.$word.'/i');
							$andArray[] = array($attribute => $regexObj);
						}

						$orArray[] = array('$and'=>$andArray);
					}
				}

				if (!empty($orArray))
					$criteria['$or'] = $orArray;
			}
	    }

		return $criteria;
	}


	/**
	 * Returns an instance of the PHP class MongoCursor after a find operation
	 * Applies sort and limit to the cursor if the params are not empty
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param array $criteria
	 * @param array $select
	 * @param array $sort
	 * @param integer $limit
	 * @return MongoCursor
	 */
	public function findCursor($criteria=array(),$select=array(),$sort=array(),$limit=null,$skip=null)
	{
		//always add the '_id'
		if (!empty($select) && !in_array('_id',$select))
			array_push($select,'_id');

		$this->_cursor = $this->getCollection()->find($criteria, $select);

        $this->callObjectMethods($this->_collection,$this->_callAfterCollectionFind,self::CALLAFTER_COLLECTIONFIND);

        if (!empty($skip) && is_numeric($skip))
            $this->_cursor->skip($skip);

		if (!empty($limit) && is_numeric($limit))
            $this->_cursor->limit($limit);

		if (!empty($sort) && is_array($sort))
            $this->_cursor->sort($sort);

        $this->callObjectMethods($this->_cursor,$this->_callAfterCursorCreate,self::CALLAFTER_CURSORCREATE);

		return $this->_cursor;
	}

	/**
	 * Returns an array of the result after a find operation
	 * Applies sort and limit to the cursor if the params are not empty
	 * Iterates through the result to return an array
	 *
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param array $criteria
	 * @param array $select
	 * @param array $sort
	 * @param integer $limit
	 * @return array
	 */
	public function findArray($criteria=array(),$select=array(),$sort=array(),$limit=null,$skip=null)
	{
	   	$result = array();

        $this->_cursor = $this->findCursor($criteria,$select,$sort,$limit,$skip);

		if (!empty($this->_cursor) && $this->_cursor->count())
			foreach ($this->_cursor as $id => $value)
			   $result[] = $value;

		return $result;
	}


	/**
	 * Returns an array of objects after a find operation
	 * Applies sort and limit to the cursor if the params are not empty
	 * Iterates through the result to return an array
	 *
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param array $criteria
	 * @param array $select
	 * @param array $sort
	 * @param integer $limit
	 * @return array
	 */
	public function findObjects($criteria=array(),$select=array(),$sort=array(),$limit=null,$skip=null)
	{
		$result = array();

        $this->_cursor = $this->findCursor($criteria,$select,$sort,$limit,$skip);

		if (!empty($this->_cursor) && $this->_cursor->count())
			foreach ($this->_cursor as $id => $value)
				$result[] = (object)$value;

		return $result;
	}

	/**
	 * Returns a DataProvider populated by the result after a find operation
	 * The 'models' are returned as array, not as objects
	 * @see EDMSDataProvider.php
	 *
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param array $criteria
	 * @param array $select the attributes to select
	 * @param array $config the dataProvider config
	 * @return EDMSDataProvider
	*/
	public function getArrayDataProvider($criteria=array(),$select=array(),$config=array())
	{
        $this->_cursor = $this->findCursor($criteria,$select);
		return new EDMSDataProvider($this->_cursor,$config);
	}

	/**
	 * Returns an array of objects of the result after a find operation
	 * The 'models' are returned as PHP standard objects 'stdClass'
	 * @see EDMSDataProvider.php
	 *
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param array $criteria
	 * @param array $select the attributes to select
	 * @param array $config the dataProvider config
	 * @return EDMSDataProvider
	 */
	public function getObjectDataProvider($criteria=array(),$select=array(),$config=array())
	{
        $this->_cursor = $this->findCursor($criteria,$select);
		return new EDMSDataProvider($this->_cursor,$config,'stdClass');
	}

	/**
	 * Returns an array of objects of the result after a find operation
	 * The 'models' are returned as objects of the specified modelClassname
	 * @see EDMSDataProvider.php
	 *
	 * Usage for yiimongodbsuite models or CFormModel
	 *
	 * Uses the preconfigured connection from the EDMSBehavior
	 *
	 * @param string $modelClassname
	 * @param array $criteria
	 * @param array $select the attributes to select
	 * @param array $config the dataProvider config
	 * @return EDMSDataProvider
	 */
	public function getModelDataProvider($modelClassname,$criteria=array(),$select=array(),$config=array())
	{
        $this->_cursor = $this->findCursor($criteria,$select);
		return new EDMSDataProvider($this->_cursor,$config,$modelClassname);
	}

	/**
	 * Uses the preconfigured connection from the EDMSBehavior to find one record
	 *
	 * @param arrqay $criteria
	 * @param array $select
	 * @return mixed array the record or false
	 */
	public function findOne($criteria,$select=array())
	{
		//always add the '_id'
		if (!empty($select) && !in_array('_id',$select))
			array_push($select,'_id');

        $this->_cursor = $this->getCollection()->findOne($criteria, $select);

		return !empty($this->_cursor) ? $this->_cursor : false;
	}


	/**
	 * Execute the collections group method
	 * @link http://www.php.net/manual/en/mongocollection.group.php
	 *
	 * @param mixed $keys
	 * @param array $initial
	 * @param string $reduce
	 * @param array $options
	 * @return array
	 */
	public function findGroup($keys,$initial,$reduce,$options)
	{
        $this->_cursor = $this->getCollection()->group($keys,$initial,new MongoCode($reduce),$options);
		$items = $this->_cursor['ok'] == 1 ? $this->_cursor['retval'] : null;
		return $items;
	}

	/**
	 * EDMSQuery::findGroupBy()
	 * @see http://www.php.net/manual/en/mongocollection.group.php
	 *
	 * Tip: get the count of the grouped values by count('_items')
	 *
	 * @param array $attributes
	 * @param array $criteria
	 * @return array of array(groupedAttributeName=>value,groupedAttributeName=>value, ...array('_items') => [additional selected attributes])
	 */
	public function findGroupBy($attributes,$select=array(),$criteria=array())
	{
		if (empty($attributes))
			return array();

		if (is_string($attributes))
			$attributes = array($attributes);
		else
		if (!is_array($attributes))
			return array();

		$keys = array();
		foreach ($attributes as $attribute)
			$keys[$attribute] = true;

		$jsDetails = array();

		//$select = array_merge(array('_id'),$select); //removed v 0.2.5

		foreach ($select as $detail)
			$jsDetails[]="out['$detail']=obj.$detail";

		$jsDetailsStr = implode(';',$jsDetails);

		$initial = array('_items' => array());
		$reduce = "function (obj, prev) { out=new Object(); $jsDetailsStr; prev._items.push(out);}";

		if (!empty($criteria))
		   $criteria = array(
				    'condition' => $criteria,
				  );

		return $this->findGroup($keys,$initial,$reduce,$criteria);
	}

	/**
	 * Find and count the specified attribute or the combination of the attributes
	 *
	 * @param mixed $attributes string or array
	 * @param array $criteria
	 * @param string $countAlias the alias for the count values
	 * @return array of array(attributeName=>value,attributeName=>value, ... countAlias=>float)
	 */
	public function findCountBy($attributes,$criteria=array(),$countAlias='count')
	{
		if (empty($attributes))
			return array();

		if (is_string($attributes))
			$attributes = array($attributes);
		else
		if (!is_array($attributes))
			return array();

		$keys = array();
		foreach ($attributes as $attribute)
			$keys[$attribute] = true;

		$initial = array($countAlias => 0);
		$reduce = "function(doc, prev) { prev.$countAlias += 1 }";

		if (!empty($criteria))
			$criteria = array(
							'condition' => $criteria,
						  );

		return $this->findGroup($keys,$initial,$reduce,$criteria);
  }

    /**
     * Find items prepared for usage with a dropdown list
     *
     * @param $values
     * @param string $keys
     * @param array $criteria
     * @param bool $sortByValues
     * @param string $keySeparator
     * @param string $valueSeparator
     * @return array
     * @since 0.2.5
     */
    public function findDropDownItems($values,$keys='_id',$criteria = array(),$sortByValues=true,$keySeparator='__',$valueSeparator=', ')
    {
        $result = array();

        if(is_string($keys))
            $keys = array($keys);

        if(is_string($values))
            $values = array($values);

        $items = $this->findGroupBy($keys,$values,$criteria);

        if(!empty($items))
        {
            foreach($items as $item)
            {
                $keyStr = '';

                foreach($keys as $key)
                    $keyStr .= empty($keyStr) ? $item[$key] : $keySeparator . $item[$key];

                $valueStr = '';
                $groupedValues = $item['_items'];

                foreach($groupedValues as $values)
                  foreach($values as $key=>$value)
                  {
                      $value = trim($value);
                      if(empty($value))
                          continue;

                      $valueStr .= empty($valueStr) ? $value : $valueSeparator . $value;
                  }

                $result[$keyStr] = $valueStr;
            }
        }

        if($sortByValues)
            asort($result);

        return $result;
    }

	/**
	 * Distinct query for a specific attribute in the collection
	 *
	 * @param string $attribute
	 * @param array $criteria additional criteria
	 * @return array
	 */
	public function findDistinct($attribute, $criteria = array(),$sort = self::SORT_ASC)
	{
		$command = array('distinct' => $this->_collectionName, 'key' => $attribute);

		if (!empty($criteria))
			array_push($command, $criteria);

		$result = $this->getDb()->command($command);
		$items = $result['ok'] == 1 ? $result['values'] : null;

		if (isset($sort) && !empty($items))
		{
			if ($sort === self::SORT_ASC)
			  sort($items);
			else
			if ($sort === self::SORT_DESC)
				rsort($items);
		}

		return $items;
	}




	/**
	 * Update parts of a record
	 *
	 * @link http://www.php.net/manual/en/mongocollection.update.php
	 * note: per default only the first found record will be updated
	 *       if not multiflag is set
     *
     * Since v0.2.5 supports multiple modifiers to
	 *
	 * @param array $criteria
	 * @param array $values
	 * @param mixed $modifier string or array
	 * @param boolean $multiple
	 * @return boolean
	 */
	public function atomicUpdate($criteria,$values,$modifier = '$set', $multiple = false,$options=array())
	{
		if (empty($values) && !is_array($modifier))
			return false;

		$action = is_array($modifier) ? $modifier : array($modifier => $values);

		if ($multiple)
		   $options = array_merge($options, array('multiple'=>true));

		return $this->getCollection()->update($criteria,$action,$options);
	}



	/**
	 * Updates all found records with matching criteria with the specified values
	 *
	 * @link http://php.net/manual/de/mongocollection.update.php
	 *
	 * @param array $criteria
	 * @param array $values
	 * @param string $modifier
	 * @param boolean $multiple
	 * @return mixed depends on 'safe' option
	 */
	public function update($criteria,$values,$options=array())
	{
		return $this->atomicUpdate($criteria,$values,'$set',true,$options);
	}

	/**
	 * If no document matches $criteria,
	 * a new document will be created from $criteria and $values
	 *
	 * @link http://php.net/manual/de/mongocollection.update.php
	 *
	 * @param array $criteria
	 * @param array $values
	 * @param array $options
	 * @return mixed depends on 'safe' option
	 */
	public function upsert($criteria,$values,$options=array())
	{
		if (empty($values))
			return false;

		$options = array_merge($options, array('upsert'=>true));
		return $this->getCollection()->update($criteria,$values,$options);
	}


	/**
	 * @link http://www.php.net/manual/en/mongocollection.insert.php
	 *
	 * @param mixed $values
	 * @param array $options
	 * @return mixed depends on 'safe' option
	 */
	public function insert($values,$options=array())
	{
		return $this->getCollection()->insert($values,$options);
	}


	/**
	 * @link http://www.php.net/manual/de/mongocollection.remove.php
	 *
	 *
	 * @param array $criteria
	 * @param boolean $justOne
	 * @param array $options
	 * @return mixed depends on 'safe' option
	 */
	public function remove($criteria,$justOne=false,$options=array())
	{
		$options['justOne'] = $justOne;
		return $this->getCollection()->remove($criteria,$options);
	}


	/**
	 * Add an item to a array attribute of all records, that matches the criteria
	 * @see PHP manual MongoCollection::update
	 * @link http://www.php.net/manual/en/mongocollection.update.php
	 *
	 * @param array $criteria
	 * @param string $attribute
	 * @param mixed $value
	 * @param array $options
	 * @return boolean
	 */
	public function addToSet($criteria,$attribute,$value,$options=array())
	{
		return $this->atomicUpdate($criteria,array($attribute=>$value),'$addToSet',true,$options);
	}

	/**
	 * Remove a item from an array attribute from all records, that matches the criteria
	 * @see PHP manual MongoCollection::update
	 * @link http://www.php.net/manual/en/mongocollection.update.php
	 *
	 * @param array $criteria
	 * @param string $attribute
	 * @param mixed $value
	 * @param array $options
	 * @return boolean
	 */
	public function removeFromSet($criteria,$attribute,$values,$options=array())
	{
		$action = array('$pull' => array(
			                              $attribute => $values,
										));

		$options = array_merge(array('multiple'=>true),$options);
		return $this->getCollection()->update($criteria,$action,$options);
	}


	/**
	 * Ensure one or multiple indexes in the collection at once
	 * @param mixed $indexes string or array
	 */
	public function ensureIndexes($indexes)
	{
		if (is_string($indexes))
			$indexes = array($indexes);

		if (is_array($indexes))
		  foreach ($indexes as $index)
			$this->getCollection()->ensureIndex($index);
	}

	/**
	 * Return the public properties of an object as array
	 *
	 * @param mixed $model
	 * @return
	 */
	public static function modelToArray($model)
	{
		if (method_exists($model,'getAttributes'))
			return $model->getAttributes(); //set model attributes with no validation
		else
		{ //assign the values to the public properties
			$class=new ReflectionClass(get_class($model));
			$attributes = array();
			foreach($model->getProperties() as $property)
			{
				if($property->isPublic() && !$property->isStatic())
				{
				  $key=$property->getName();
				  $attributes[$key] = $model->$key;
				}
			}

			return $attributes;
		}
	}


	/**
	 * Assign attributes to the public properties of a object / model
	 * if $modelProperties is null, the public, non static properties will be
	 * extracted by using the models ReflectionClass
	 *
	 * @param mixed $attributes
	 * @param mixed $className
	 * @return
	 */
	public static function arrayToModel($attributes,$className,$modelProperties = null)
	{
		$model = new $className;

		//assign the $modelProperties
		if (isset($modelProperties))
		{
			foreach($modelProperties as $key)
			{
				if(array_key_exists($key,$attributes))
					$model->$key = $attributes[$key];
			}
		}
		else
		if (method_exists($model,'setAttributes'))
			$model->setAttributes($attributes,false); //set model attributes with no validation
		else
		//get the public, non static properties of the model
		{
			$reflectionClass=new ReflectionClass($model);
			foreach($reflectionClass->getProperties() as $property)
			{
				$key=$property->getName();
				if($property->isPublic() &&
				  !$property->isStatic() &&
					array_key_exists($key,$attributes))
					   $model->$key = $attributes[$key];
			}
		}

		return $model;
	}

    /**
     * Add key=>$value to a criteria array
     * @static
     * @param $key
     * @param $value
     * @param null $criteria
     * since 0.2.5
     */
    public static function addCriteria($key,$value,&$criteria = null)
    {
        if(!isset($criteria))
            $criteria = array();

        $criteria[$key] = $value;
    }

}
