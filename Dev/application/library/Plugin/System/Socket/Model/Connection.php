<?php

namespace Model;
use ActiveRecord, System;

class Connection extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_socket_connections';

    static array $schema = array(
        "id"                => "bigint(250) NOT NULL AUTO_INCREMENT",
        "user_id"           => "bigint(250) NOT NULL DEFAULT 0",
        "session_id"        => "bigint(250) NOT NULL",
        "socket"            => "text",
        "socket_session"    => "text",
        "data"              => "text",
        "connected_on"      => "bigint(250) NOT NULL DEFAULT 0",
        "created_at"        => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"        => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
    );

}





























