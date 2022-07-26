<?php

namespace Module\Admin;

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
    /**
     * Disable email validation for staff
     * @var bool
     */
    protected $skip_user_email_validation_check = true;

    /**
     * Check if the user has access to the backend
     * @return bool
     * @throws \Exception
     */
    protected function authentication()
    {
        if(parent::authentication()
            && $this->user->is_tag_present(SYSTEM_USER_TYPE_STAFF)) {

            return true;
        }
        /*
        setcookie("access_token", 0, time()-(60*60*24), "/", $this->kernel->config('cookie_domain'), 1);
        if($this->kernel->request()->isAjax()) {
            return array(
                'error' => 'session_expired'
            );
        } else {
            $this->kernel->redirect('/login/?err=2');
            return false;
        }*/
    }

    public function indexAction(\System\Request $request)
    {
        $this->view->helper('head')->script('admin_script/route.js');
        return $this->view;
    }

}




















