<?php

namespace Module\Userdetails\Profilepicture;

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
        try {
            $user = \Model\Session::load_user()->user;
            $image = $_FILES['profile_pic'];
            $extension = pathinfo($image['name'], PATHINFO_EXTENSION);

            $imageName = rand(10, 100) . time();

            $server_location = $this->kernel->config('app.profile_picture.path') . '/' . $imageName . '.' . $extension;

            if (move_uploaded_file($image["tmp_name"], $server_location)) {
                $user->profile_pic = $server_location;
                $user->save();
                return array(
                    "success" => "Profile picture updated successfully!",
                    "error" => ""
                );
            } else {
                return array(
                    "success" => "",
                    "error" => "Sorry, something went wrong."
                );
            }
        } catch (\Throwable $th) {
            ___debug($th->getMessage());
        }
    }
}
