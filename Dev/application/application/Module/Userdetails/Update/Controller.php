<?php

namespace Module\Userdetails\Update;

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
        $tenant = \Model\Tenant::find($user->c_tenant);

        if ($data['first_name'] && $data['last_name']) {
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];

            $user->save();

            $tenant->name = $data['first_name'] . " " . $data['last_name'];
        }

        if (!empty($data['country'])) {
            $tenant->country = $data['country'];
        }

        $tenant->save();

        return array(
            "success" => "Profile updated successfully!",
            "error" => ""
        );
    }
}
