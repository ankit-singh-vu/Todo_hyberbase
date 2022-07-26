<?php

namespace Model;

use ActiveRecord, System;

class Subscription extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'subscriptions';

    static array $schema = array(
        "id"           => "bigint(250) NOT NULL AUTO_INCREMENT",
        "uuid"         => "text NOT NULL",
        "pg_id"        => "bigint(250) NOT NULL DEFAULT 0", //Payment gateway identifier
        "tenant_id"    => "bigint(250) NOT NULL DEFAULT 0",
        "plan_id"      => "bigint(250) NOT NULL DEFAULT 0", // Package Id (from table `packages`)
        "payment_plan" => "bigint(250) NOT NULL DEFAULT 0",
        "trial_end"    => "bigint(250) NOT NULL DEFAULT 0",
        "pg_data"      => "text",
        "created_at"   => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"   => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"       => "int(5) NOT NULL DEFAULT '0'"
    );

    static array $belongs_to = array(
        array('tenant'), array('plan')
    );

    static array $has_many = array(
        array('subscriptionlogs')
    );

    public function createMail($name, $email, $packageName, $websiteLink)
    {
        \Kernel()->notify(array(
            'Email' => array(
                'to'        => $email,
                'subject'   => 'New Advisor Learn Subscription',
                'template'  => 'subscription.create.email',
                'variables' => array(
                    'NAME'          => $name,
                    'DASHBOARD_URL' => $websiteLink,
                    'PACKAGE_NAME'  => $packageName
                )
            )
        ));
    }

    public function suspendMail($name, $email, $packageName, $websiteLink)
    {
        \Kernel()->notify(array(
            'Email' => array(
                'to'        => $email,
                'subject'   => 'Suspension of Advisor Learn Subscription',
                'template'  => 'subscription.suspend.email',
                'variables' => array(
                    'NAME'          => $name,
                    'DASHBOARD_URL' => $websiteLink,
                    'PACKAGE_NAME'  => $packageName
                )
            )
        ));
    }

    public function activateMail($name, $email, $packageName, $websiteLink)
    {
        \Kernel()->notify(array(
            'Email' => array(
                'to'        => $email,
                'subject'   => 'Activation of Advisor Learn Subscription',
                'template'  => 'subscription.activate.email',
                'variables' => array(
                    'NAME'          => $name,
                    'DASHBOARD_URL' => $websiteLink,
                    'PACKAGE_NAME'  => $packageName
                )
            )
        ));
    }
}
