<?php
namespace Module\System;
use System, System\Exception;

/**
 * Assets Module
 * 
 */
class Controller extends System\Module\ControllerAbstract
{
    public function logoutAction(System\Request $request)
    {
        if(isset($_COOKIE["access_token"])) {
            $session = \Model\Session::find_by_token($_COOKIE["access_token"]);
            if($session) {
                $session->delete();
            }
            setcookie("access_token", 0, time() - (60 * 60 * 24), "/", $this->kernel->config('cookie_domain'), 1);
        }
        $this->kernel->redirect('/login');
    }

}