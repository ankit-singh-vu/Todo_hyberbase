<?php

namespace Model;
use ActiveRecord, System;

class Variable extends \ActiveRecord\Model
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_db_variables';

    /**
     * @var array
     */
    static array $schema = array(
        'id'          => 'bigint(250) NOT NULL AUTO_INCREMENT',
        'table_name'  => 'text NOT NULL',
        'record_id'   => 'bigint(250) NOT NULL DEFAULT \'0\'',
        'name'        => 'text NOT NULL',
        'data'        => 'text',
        'created_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
        'updated_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\''
    );

}





























