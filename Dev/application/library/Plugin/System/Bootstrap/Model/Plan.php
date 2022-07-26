<?php

namespace Model;
use ActiveRecord, System;

class Plan extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plans';

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "uuid"        => "text NOT NULL",
        "name"        => "text NOT NULL",
        "cost"        => "bigint(250) NOT NULL DEFAULT 0",
        "currency"    => "text NOT NULL",

        "plan_type"   => "int(5) NOT NULL DEFAULT '0'",
        "pg_id"       => "bigint(250) NOT NULL DEFAULT 0",
        "pg_name"     => "text NOT NULL",
        "billing_type"=> "text NOT NULL",
        "billing_period" => "int(5) NOT NULL DEFAULT '0'",

        "private"     => "int(1) NOT NULL DEFAULT '0'",
        "pg_data"     => "text NOT NULL", //contains all payment-gateway related info to identify the plan
        "status"      => "int(5) NOT NULL DEFAULT '0'",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
    );

}





























