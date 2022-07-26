<?php

//require_once('MongoRecord.php');
//require_once('MongoRecordIterator.php');
//require_once('Inflector.php');

namespace ActiveRecord\Mongo;

use ActiveRecord\Mongo\MongoRecordIterator as MongoRecordIterator;
use ActiveRecord\Mongo\Inflector as Inflector;
use ActiveRecord\Mongo\MongoRecord as MongoRecord;

abstract class Model implements MongoRecord
{
    protected $attributes;
    protected $errors;
    private $new;

    public static $database = null;
    public static $connection = null;
    public static $findTimeout = 20000;

    /**
     * Collection name will be generated automaticaly if setted to null.
     * If overridden in child class, then new collection name uses.
     *
     * @var string
     */
    protected static $collectionName = null;

    public function __construct($attributes = array(), $new = true)
    {
        if (isset($attributes['id']) and !isset($attributes['_id'])) {
            $attributes['_id'] = $attributes['id'];
            unset($attributes['id']);
        }
        $this->new = $new;
        $this->attributes = $attributes;
        $this->errors = array();

        if ($new) {
            $this->afterNew();
        }
    }

    public function validate()
    {
        $this->beforeValidation();
        $retval = $this->isValid();
        $this->afterValidation();
        return $retval;
    }

    public function save(array $options = array())
    {
        if (!$this->validate()) {
            return false;
        }

        $this->beforeSave();

        $collection = self::getCollection();
        $collection->save($this->attributes, $options);

        $this->new = false;
        $this->afterSave();

        return true;
    }

    public function destroy()
    {
        $this->beforeDestroy();

        if (!$this->new) {
            $collection = self::getCollection();
            $collection->remove(array('_id' => $this->attributes['_id']));
        }
    }
    
    public function delete()
    {
        return $this->destroy();
    }
    
    private static function _find($query = array(), $options = array())
    {
        $collection = self::getCollection();
        if (isset($options['fields'])) {
            $documents = $collection->find($query, $options['fields']);
        } else {
            $documents = $collection->find($query);
        }
                

        $className = get_called_class();

        if (isset($options['sort'])) {
            $documents->sort($options['sort']);
        }

        if (isset($options['offset'])) {
            $documents->skip($options['offset']);
        }

        if (isset($options['limit'])) {
            $documents->limit($options['limit']);
        }

    
        $documents->timeout($className::$findTimeout);
        return $documents;
    }
    public static function findAll($query = array(), $options = array())
    {
        $documents = static::_find($query, $options);
        $ret = array();
        while ($documents->hasNext()) {
            $document = $documents->getNext();
            $ret[] = self::instantiate($document);
        }

        return $ret;
    }

    public static function find($query = array(), $options = array())
    {
        $documents = static::_find($query, $options);
        $className = get_called_class();
        return new MongoRecordIterator($documents, $className);
    }

    public static function findOne($query = array(), $options = array())
    {
        $options['limit'] = 1;

        $results = self::find($query, $options);

        if ($results) {
            return $results->current();
        } else {
            return null;
        }
    }

    public static function count($query = array())
    {
        $collection = self::getCollection();
        $documents = $collection->count($query);

        return $documents;
    }

    private static function instantiate($document)
    {
        if ($document) {
            $className = get_called_class();
            return new $className($document, false);
        } else {
            return null;
        }
    }

    public function getID()
    {
        return $this->attributes['_id'];
    }

    public function setID($id)
    {
        $this->attributes['_id'] = $id;
        return $this;
    }
    
    public static function create(array $attributes)
    {
        $className    = get_called_class();
        $db = new $className($attributes);
        $db->save();
        return $db;
    }
    
    /**
     *
     * @param type $key
     * @param type $value
     * @return \ActiveRecord\Mongo\Model
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }
    
    /**
     *
     * @param string $key
     * 
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key];
    }//end __get()

    /**
     * Class __call method.
     * 
     * @param string $method    Name of the Method.
     * @param array  $arguments List of Arguments.
     * 
     * @throws Exception Throws exception in case of error.
     * 
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        // Is this a get or a set.
        $prefix = strtolower(substr($method, 0, 3));
        if ($prefix != 'get' && $prefix != 'set') {
            return;
        }
        // What is the get/set class attribute.
        $inflector = Inflector::getInstance();
        $property  = $inflector->underscore(substr($method, 3));
        if (empty($prefix) || empty($property)) {
            // Did not match a get/set call.
            throw new Exception("Calling a non get/set method that does not exist: $method");
        }
        // Get.
        if ($prefix == "get" && array_key_exists($property, $this->attributes)) {
            return $this->attributes[$property];
        } elseif ($prefix == "get") {
            return null;
        }
        // Set.
        if ($prefix == "set" && array_key_exists(0, $arguments)) {
            $this->attributes[$property] = $arguments[0];
            return $this;
        } else {
            throw new Exception("Calling a get/set method that does not exist: $property");
        }
    }//end __call()

    /**
     * Framework overrides/callbacks.
     * Triggers before saving a record.
     * 
     * @return void 
     */
    public function beforeSave()
    {
    }//end beforeSave()
    
    /**
     * Framework overrides/callbacks.
     * Triggers after saving a record.
     * 
     * @return void 
     */
    public function afterSave()
    {
    }//end afterSave()
    
    /**
     * Framework overrides/callbacks.
     * Triggers before validating a record.
     * 
     * @return void 
     */
    public function beforeValidation()
    {
    }//end beforeValidation()
    
    /**
     * Framework overrides/callbacks.
     * Triggers after validating a record.
     * 
     * @return void 
     */
    public function afterValidation()
    {
    }//end afterValidation()
    
    /**
     * Framework overrides/callbacks.
     * Triggers before destroying a record. 
     * 
     * @return void
     */
    public function beforeDestroy()
    {
    }//end beforeDestroy()
    
    /**
     * Framework overrides/callbacks.
     * Triggers after saving a record. 
     * 
     * @return void
     */
    public function afterNew()
    {
    }//end afterNew()

    /**
     * Validate the Model.
     * 
     * @return boolean
     */
    protected function isValid()
    {
        $className = get_called_class();
        $methods   = get_class_methods($className);
        foreach ($methods as $method) {
            if (substr($method, 0, 9) == 'validates') {
                $propertyCall = 'get' . substr($method, 9);
                if (!$className::$method($this->$propertyCall())) {
                    return false;
                }
            }
        }
        return true;
    }//end isValid()

    /**
     * Core Conventions. 
     * 
     * @throws Exception Throws an exception in case of error.
     * 
     * @return mixed
     */
    protected static function getCollection()
    {
        $className = get_called_class();
        if (null !== static::$collectionName) {
            $collectionName = static::$collectionName;
        } else {
            $inflector      = Inflector::getInstance();
            $collectionName = $inflector->tableize($className);
        }
        if ($className::$database == null) {
            throw new \Exception("BaseMongoRecord::database must be initialized to a proper database string");
        }
        if ($className::$connection == null) {
            throw new \Exception("BaseMongoRecord::connection must be initialized to a valid Mongo object");
        }
        if (!($className::$connection->connected)) {
            $className::$connection->connect();
        }
        return $className::$connection->selectCollection($className::$database, $collectionName);
    }//end getCollection()

    /**
     * Set Query TimeOut.
     * 
     * @param intiger $timeout The desired timeout span ion seconds.
     * 
     * @return void
     */
    public static function setFindTimeout($timeout)
    {
        $className = get_called_class();
        $className::$findTimeout = $timeout;
    }//end setFindTimeout()

    /**
     * Indexes a specific key.
     * 
     * @param array $keys    The $key to be indexed.
     * @param array $options Additional Options.
     * 
     * @return mixed
     */
    public static function ensureIndex(array $keys, array $options = array())
    {
        return self::getCollection()->ensureIndex($keys, $options);
    }//end ensureIndex()
    
    /**
     * Delets a key indexing.
     * 
     * @param string $keys The Key whose indexing needs to be deleted.
     * 
     * @return mixed
     */
    public static function deleteIndex($keys)
    {
        return self::getCollection()->deleteIndex($keys);
    }//end deleteIndex()
    
    /**
     * Returns the attribute array.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }//end getAttributes()
}//end class
