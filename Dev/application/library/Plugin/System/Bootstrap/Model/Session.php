<?php

namespace Model;
use ActiveRecord, System;

class Session extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'sessions';

    static array $schema = array(
        "id"          => "bigint(250) NOT NULL AUTO_INCREMENT",
        "user_id"     => "bigint(250) NOT NULL",
        "access_id"   => "bigint(250) NOT NULL",
        "token"       => "text NOT NULL",
        "data"        => "text",
        "last_access" => "bigint(250) NOT NULL DEFAULT '0'",
        "created_at"  => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"  => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
    );

    static array $belongs_to = array(
        array('user'), array('accesss')
    );

    public static function _load($access_token=null)
    {
        return static::load_user($access_token);
    }

    public static function load_user($access_token=null)
    {
        if($access_token == null) {
            if(isset($_COOKIE["access_token"])) {
                $access_token = $_COOKIE["access_token"];
            }
            if(isset($_REQUEST["access_token"])) {
                $access_token = $_REQUEST["access_token"];
            }
            foreach (getallheaders() as $name => $value) {
                if($name == 'access-token') {
                    $access_token = $value;
                    break;
                }
            }
            if($access_token == null) {
                return null;
            }
        }
        return \Model\Session::find_by_token($access_token);
    }

    public static function register(\Model\Access $access)
    {
        $existing_session = self::find_by_access_id($access->id);
        if($existing_session) {
            $existing_session->delete();
        }
        return self::create(array(
            "user_id"     => $access->user->id,
            "access_id"   => $access->id,
            "token"       => gen_uuid() . '-' . gen_uuid()
        ));
    }

    /**
     * Fix for access keyword
     *
     * @param string $name
     * @return mixed
     * @throws ActiveRecord\Exception
     * @throws ActiveRecord\UndefinedPropertyException
     */
    public function &__get($name)
    {
        if($name == 'access') {
            return $this->read_attribute('accesss');
        }
        return parent::__get($name);
    }



}





























