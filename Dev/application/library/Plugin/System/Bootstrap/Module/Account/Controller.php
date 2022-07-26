<?php

namespace Module\Account;

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
        //dd_help();
        $this->setPointers('account');
        return $this->view;
    }
}




















