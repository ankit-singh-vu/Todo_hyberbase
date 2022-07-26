<?php

namespace Module\Profile;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends App\Module\ControllerAbstract
{
    public function indexAction(System\Request $request)
    {
        $user = \Model\Session::load_user();
        $this->view->userDetails = $user->user;

        if ($user->user->profile_pic) {
            $imageData = base64_encode(file_get_contents($user->user->profile_pic));

            $src = 'data: ' . mime_content_type($user->user->profile_pic) . ';base64,' . $imageData;
            $this->view->profile_pic = $src;
        } else {
            $this->view->profile_pic = NULL;
        }
        
        $this->setPointers('profile');
        return $this->view;
    }

    public function postMethod(System\Request $request)
    {

        $data = $request->jsonPost();

        $user = \Model\User::find(\Model\Session::load_user()->user->id);

        if ($data['first_name'] && $data['last_name']) {
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];

            $user->save();
        }

        return array(
            "success" => "Profile updated successfully!",
            "error" => ""
        );
    }
}
