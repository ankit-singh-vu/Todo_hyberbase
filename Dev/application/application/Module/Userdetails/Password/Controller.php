<?php

namespace Module\Userdetails\Password;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends \Module\Admin\Controller
{
    public function postMethod(System\Request $request)
    {

        $data = $request->jsonPost();

        $user = \Model\User::find($data['user_id']);

        $password = $data['password'];
        $repassword = $data['repassword'];
        if($password != $repassword){
            return array(
                "success" => "",
                "error" => "Passwords must be same"
            );
        }

        $user->save();

        $access = \Model\Access::find_by_user_id($user->id);
        $access->access_secret = md5($password);

        $access->save();

        return array(
            "success" => "Password changed successfully",
            "error" => ""
        );

    }

}

