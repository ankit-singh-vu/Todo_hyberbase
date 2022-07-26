<?php

namespace Module\Profile\Password;

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

        $user = \Model\User::find(\Model\Session::load_user()->user->id);

        $new_password = $data['new_password'];
        $re_password = $data['re_password'];

        $user->save();

        $access = \Model\Access::find_by_user_id($user->id);
        
        if ($new_password != $re_password) {
            return array(
                "success" => "",
                "error" => "New Passwords must be same"
            );
        }

        $access->access_secret = md5($new_password);

        $access->save();

        return array(
            "success" => "Password changed successfully",
            "error" => ""
        );
    }
}
