<?php

namespace Model;
use ActiveRecord, System;

class Otalink extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'ota_links';

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "key"         => "text NOT NULL",
        "action"      => "text NOT NULL",
        "expiry"      => "bigint(250) NOT NULL DEFAULT 0",
        "data"        => "text NOT NULL",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
    );

}





























