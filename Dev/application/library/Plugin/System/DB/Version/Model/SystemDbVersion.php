<?php

namespace Model;
use ActiveRecord, System;

class SystemDbVersion extends \ActiveRecord\Model
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_db_version';

    /**
     * @var array
     */
    static array $schema = array(
        'id'          => 'bigint(250) NOT NULL AUTO_INCREMENT',
        'table_name'  => 'text NOT NULL',
        'version'     => 'bigint(250) NOT NULL DEFAULT \'0\'',
        'created_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\'',
        'updated_at'  => 'timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\''
    );

    /**
     * @param $number
     * @return bool
     */
    public function version($number)
    {
        $this->version = $number;
        return $this->save();
    }

}





























