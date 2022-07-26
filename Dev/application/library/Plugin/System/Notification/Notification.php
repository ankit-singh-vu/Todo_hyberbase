<?php

namespace Plugin\System\Notification;
use System, RuntimeException;

abstract class Notification extends \ActiveRecord\Model
{
    abstract static public function send($params);
}





























