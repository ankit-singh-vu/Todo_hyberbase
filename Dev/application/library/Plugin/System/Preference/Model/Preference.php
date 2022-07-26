<?php

namespace Model;
use ActiveRecord, System;

class Preference extends \ActiveRecord\Model
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_preference_preference';

    static array $schema = array(
      "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
      "key_name"    => "text NOT NULL",
      "key_value"   => "text NOT NULL",
      "object_name" => "text NOT NULL",
      "data_type"   => "text NOT NULL",
      "object_id"   => "bigint(250) NOT NULL",
      "updated_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
      "created_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
    );

}





























