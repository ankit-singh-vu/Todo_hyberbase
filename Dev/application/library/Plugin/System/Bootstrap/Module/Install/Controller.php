<?php
namespace Module\Install;
use System, System\Exception;

/**
 * Assets Module
 * 
 */
class Controller extends System\Module\ControllerAbstract
{
    /**
     * Index
     * @method GET
     */
    public function indexAction(System\Request $request)
    {
        if(!$request->isCli()) {
            return false;
        }
        echo 'Running Pre Install...'."\n";
        $this->kernel->events('system')->trigger('pre_install');
        echo 'Running Install...'."\n";
        $this->kernel->events('system')->trigger('install');
        echo 'Running Post Install...'."\n";
        $this->kernel->events('system')->trigger('post_install');
        echo 'Application Installed/Updated'."\n";
        exit;
    }
}