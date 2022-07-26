<?php

namespace App;
use ActiveRecord, System;

abstract class Permission extends \Model\AttributeAbstract
{
    /**
     * Searches object db based on user prevelage
     * @param $keyword
     * @param \Model\User $user
     * @return array
     */
    public function search($keyword, \Model\User $user)
    {
        return array();
    }

    /**
     * Checks if a user has access to this projects
     * @param \Model\User $user
     * @return bool
     */
    public function has_access(\Model\User $user)
    {
        return false;
    }

    /**
     * Validates user's permission agains a given key
     * @param $key
     * @param \Model\User $user
     * @return bool
     */
    public function permissions($key, \Model\User $user)
    {
        return false;
    }

    /**
     * Returns the segmant navigation data for this object
     * @param $segment
     * @param \Model\User $user
     * @return array
     */
    public function get_section_navigations($segment, \Model\User $user)
    {
        return array();
    }


}





























