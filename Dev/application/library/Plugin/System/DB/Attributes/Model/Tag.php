<?php

namespace Model;
use ActiveRecord, System;

class Tag extends \ActiveRecord\Model
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_db_tag';

    /**
     * @var array
     */
    static array $schema = array(
        'id'          => 'bigint(250) NOT NULL AUTO_INCREMENT',
        'table_name'  => 'text NOT NULL',
        'record_id'   => 'bigint(250) NOT NULL DEFAULT \'0\'',
        'tag'         => 'text NOT NULL',
        'created_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
        'updated_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\''
    );

}




























