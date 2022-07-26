<?php

namespace ActiveRecord\Mongo;

use ActiveRecord\Mongo\MongoRecordIterator as MongoRecordIterator;
use ActiveRecord\Mongo\Inflector as Inflector;
use ActiveRecord\Mongo\Model as BaseMongoRecord;

interface MongoRecord
{
	public static function setFindTimeout($timeout);
	public static function find($query);
	public static function findOne($query);
}

