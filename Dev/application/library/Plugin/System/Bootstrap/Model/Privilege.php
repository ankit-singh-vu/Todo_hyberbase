<?php

namespace Model;
use ActiveRecord, System;

class Privilege extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'privileges';

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "role_id"     => "bigint(250) NOT NULL DEFAULT 0",
        "user_id"     => "bigint(250) NOT NULL DEFAULT 0",
        "tenant_id"   => "bigint(250)",
        "name"        => "text",
        "status"      => "int(1) NOT NULL DEFAULT '0'",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
    );

    static array $belongs_to = array(
        array('user'), array('tenant'), array('role')
    );

    static public function get_permission($key, $user)
    {

    }

    static public function add_permission($object, $key, $value)
    {

    }

}





























