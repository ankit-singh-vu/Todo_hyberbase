<?php

namespace Model;
use ActiveRecord, System;

class User extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'users';

    static array $belongs_to = array(
        array('tenant')
    );

    static array $has_many = array(
        array('accesses'), array('privileges')
    );

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "uuid"        => "text NOT NULL",
        "email"       => "text NOT NULL",
        "first_name"  => "text NOT NULL",
        "middle_name" => "text NOT NULL",
        "last_name"   => "text NOT NULL",
        "profile_pic"   => "text NULL",
        "c_tenant"    => "bigint(250)",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"      => "int(5) NOT NULL DEFAULT '0'",
        "user_type"   => "int(5) NOT NULL DEFAULT '0'"
    );


    public function get_current_web_tenant()
    {
        return \Model\Tenant::find($this->c_tenant);
    }

    public function get_session()
    {
        return \Model\Session::find_by_user_id($this->id);
    }

    public function send($event, $params=array())
    {
        if(\Kernel()->isPluginLoaded('System_Socket')) {
            return \Kernel()->socket_message($this, $event, $params);
        }
        return false;
    }

}





























