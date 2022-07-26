<?php

namespace Model;
use ActiveRecord, System;

class Invoice extends \App\Permission
{
    /**
     * Name of the table related to the User Model
     * //This is called module in the course creator's language
     *
     * @var string
     */
    static string $table_name = 'invoices';

    static array $schema = array(
        "id"                => "bigint(250) NOT NULL AUTO_INCREMENT",
        "invdate"           => "bigint(250) NOT NULL DEFAULT 0",
        "tenant_id"         => "bigint(250) NOT NULL DEFAULT 0",
        "package_id"        => "bigint(250) NOT NULL DEFAULT 0",
        "payment_plan"      => "bigint(250) NOT NULL DEFAULT 0",
        "qb_invoice_id"     => "text DEFAULT NULL",
        "description"       => "text DEFAULT NULL",
        "currency"          => "text DEFAULT NULL",
        "plan_price"        => "bigint(250) NOT NULL DEFAULT 0",
        "discount"          => "bigint(250) NOT NULL DEFAULT 0",
        "amount"            => "bigint(250) NOT NULL DEFAULT 0",
        "status"            => "int(3) NOT NULL DEFAULT '0'",
        "weight"            => "int(3) NOT NULL DEFAULT '0'",
        "created_at"        => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"        => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
    );

}





























