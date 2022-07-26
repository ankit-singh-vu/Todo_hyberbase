<?php

namespace Model;
use ActiveRecord, System;

class Todo extends \App\Permission
{
    /**
     * Name of the table related to the User Model
     * //This is called module in the course creator's language
     *
     * @var string
     */
    static string $table_name = 'todos';

    static array $schema = array(
        "id"                => "bigint(250) NOT NULL AUTO_INCREMENT",
        "description"       => "text DEFAULT NULL",
        "is_striked"        => "int(3) NOT NULL DEFAULT '0'",
        "created_at"        => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"        => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
    );

}





























