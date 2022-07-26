<?php

namespace Model;
use ActiveRecord, System;

class Access extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'access';

    static array $schema = array(
        "id"            => "bigint(250) NOT NULL AUTO_INCREMENT",
        "user_id"       => "bigint(250) NOT NULL",
        "access_type"   => "int(1) NOT NULL DEFAULT '0'",
        "tenant"        => "bigint(250) NOT NULL DEFAULT '0'",
        "access_key"    => "text NOT NULL",
        "access_secret" => "text NOT NULL",
        "created_at"    => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"    => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"        => "int(5) NOT NULL DEFAULT '0'",
        "last_access"   => "bigint(250) NOT NULL DEFAULT '0'"
    );

    static $has_many = array(
        array('sessions')
    );

    static $belongs_to = array(
        array('user')
    );

    public function getTenant()
    {
        return \Model\Tenant::find($this->user->c_tenant);
    }

}





























