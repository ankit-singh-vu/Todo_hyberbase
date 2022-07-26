<?php

namespace Model;
use ActiveRecord, System;

class Role extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'roles';

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "name"        => "text NOT NULL",
        "tenant_id"   => "bigint(250)",
        "user_id"     => "bigint(250)",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"      => "int(5) NOT NULL DEFAULT '0'"
    );

    static array $belongs_to = array(
        array('user'), array('tenant')
    );

    static array $has_many = array(
        array('privileges')
    );

}





























