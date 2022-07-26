<?php

namespace Model;
use ActiveRecord, System;

class Department extends \Model\AttributeAbstract
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'departments';

    static array $schema = array(
        "id"                => "bigint(250) NOT NULL AUTO_INCREMENT",
        "name"              => "text NOT NULL",
        "protocol"          => "text NOT NULL",
        "support"           => "int(3) NOT NULL DEFAULT '0'",
        "created_at"        => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "updated_at"        => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
        "status"            => "int(5) NOT NULL DEFAULT '0'"
    );

    public function email()
    {
        return $this->protocol . '@domain.com';
    }

    /**
     * Add a user to the department
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function addUser(\Model\User $user)
    {
        if(!defined('SYSTEM_USER_TYPE_STAFF') || !$user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) {
            throw new \Exception('Invalid user department assignment');
        }
        $_department = $user->get_variable('departments');
        if($_department == false) {
            $_department = array();
        }
        $_department[] = $this->id;
        if(!in_array($this->id, $_department)) {
            $user->set_variable('departments', $_department);
        }
        return true;
    }

    /**
     * Removes an user from the department
     *
     * @param User $user
     * @return bool
     * @throws ActiveRecord\ActiveRecordException
     */
    public function rmUser(\Model\User $user)
    {
        if(!defined('SYSTEM_USER_TYPE_STAFF') || !$user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) {
            throw new \Exception('Invalid user department assignment');
        }
        $_department = $user->get_variable('departments');
        if($_department == false) {
            $_department = array();
        }
        if(in_array($this->id, $_department)) {
            $new_list = array();
            foreach($_department as $did) {
                if($did != $this->id) {
                    $new_list[] = $did;
                }
            }
            if(count($new_list) == 0) {
                $user->rm_variable('departments');
            } else {
                $user->set_variable('departments', $new_list);
            }
        }
        return true;
    }

    /**
     * Checks if the specific department has the given $user
     *
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public function hasUser(\Model\User $user)
    {
        if(!defined('SYSTEM_USER_TYPE_STAFF') || !$user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) {
            throw new \Exception('Invalid user department assignment');
        }
        $_department = $user->get_variable('departments');
        if($_department == false) {
            $_department = array();
        }
        if(in_array($this->id, $_department)) {
            return true;
        }
        return false;
    }

    /**
     * Get all the users of the specific department
     *
     * @todo: This not a very efficiant way of getting the task done.
     * @todo: it will work for now, but we need a better alternative
     *
     * @throws ActiveRecord\ActiveRecordException
     */
    public function getAllUsers()
    {
        $users = array();
        foreach(\Model\Variable::find_all_by_table_name_and_name('users', 'departments') as $entry) {
            $user = \Model\User::find($entry->record_id);
            if(in_array($this->id, json_decode(str_replace('__@@_HB_JSON_DATA=', '', $entry->data), true))) {
                $users[] = $user;
            }
        }
        return $users;
    }

}





























