<?php

namespace Module\Dashboard;

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
        $this->setPointers('dashboard');

        $this->view->dashLayout = $this->kernel->config('app.path.layouts') . '/partial/' .
            $this->kernel->config('app.plugin.system.bootstrap.dashboard.layout');
        return $this->view;
    }

    public function socketAction(System\Request $request)
    {
        //___debug(\Model\User::find(1)->send('demo', array('message' => 'Hello World @ ' . date('U'))));
        return false;
    }
}




















