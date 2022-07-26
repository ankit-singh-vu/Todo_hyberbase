<?php
namespace App;

final class Session
{
    public static function user()
    {
        if(isset($_COOKIE["access_token"])) {
            return \Model\Session::load_user();
        }
        return 0;
    }

}