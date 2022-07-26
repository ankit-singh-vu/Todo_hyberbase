<?php

namespace Model;
use ActiveRecord, System;

class Tenant extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'tenants';

    static array $has_many = array(
      array('users'), array('privileges'), array('roles')
    );

    static array $schema = array(
        "id"                 => "bigint(250) NOT NULL AUTO_INCREMENT",
        "uuid"               => "text NOT NULL",
        "email"              => "text NOT NULL",
        "name"               => "text NOT NULL",
        "data"               => "text",
        "create_ts"          => "bigint(250)",
        "user_id"            => "bigint(250)",
        "qb_customer_id"     => "text NULL",
        "created_at"         => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"         => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"             => "int(5) NOT NULL DEFAULT '0'",
        "tenant_type"        => "int(5) NOT NULL DEFAULT '0'",
        "total_spent"        => "bigint(250) NULL DEFAULT '0'",
        "currency"           => "text DEFAULT NULL",
        "country"            => "text DEFAULT 'USA'",
        "gender"             => "text NULL DEFAULT 'Male'",
        "company"            => "text DEFAULT NULL",
        "experience"         => "text DEFAULT NULL",
        "phone"              => "text DEFAULT NULL",
        "type"               => "text DEFAULT NULL",
        "language"           => "text DEFAULT 'English'",
    );

    static array $belongs_to = array(
        array('user')
    );

    public function addRole($name)
    {
        return \Model\Role::find_or_create_by_tenant_id_and_name($this->id, $name);
    }

    public function removeRole($name)
    {
        $role = \Model\Role::find_by_tenant_id_and_name($this->id, $name);
        if($role instanceof \Model\Role) {
            $role->delete();
        }
        return;
    }

    public function setUser(\Model\User $user, \Model\Role $role)
    {
        $privilage = \Model\Privilege::find_or_create_by_user_id_and_tenant_id($user->id, $this->id);
        $privilage->role_id = $role->id;
        $privilage->save();
        return;
    }

    public function subscriptionCount()
    {
        return \Model\Subscription::count_by_tenant_id($this->id);
    }

    public function send_socket_message($event, array $params = array())
    {
        foreach(\Model\Privilege::find_all_by_tenant_id($this->id) as $perm) {
            \Kernel()->socket_message(\Model\User::find($perm->user_id), $event, $params);
        }
        return true;
    }

}





























