<?php

namespace Module\Bindex;

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
        //___debug($this->user);
        if($this->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) || $this->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN)) {
            $this->kernel->redirect('/admin/');
        } else {
            $this->view->helper('head')->script('script/route.js');
            $this->kernel->redirect('/dashboard/');
        }
        return $this->view;
    }
}




















