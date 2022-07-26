<?php

namespace Module\Admin\Dashboard;

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
    public function indexAction(System\Request $request)
    {
        $this->setPointers('dashboard');
        $this->view->dashLayout = $this->kernel->config('app.path.layouts') . '/partial/' .
            $this->kernel->config('app.plugin.system.bootstrap.backend.dashboard.layout');
        return $this->view;
    }
}




















