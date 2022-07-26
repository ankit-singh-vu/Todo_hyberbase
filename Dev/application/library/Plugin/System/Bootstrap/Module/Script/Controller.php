<?php

namespace Module\Script;

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
    //protected $authentication = false;
    //protected $register_callbacks = false;
    //protected $skip_authentication = array('initexec');

    public function routeAction(System\Request $request)
    {
        $allNav = array();
        //___debug($this->kernel->events('ui')->filter('ajax_navigation', array()));
        foreach($this->kernel->events('ui')->filter('ajax_navigation', array()) as $params) {
            $n = $this->kernel->events('ui')->filter('load_navigation', array(
                'params' => $params,
                'data' => array()
            ));
            $allNav = array_merge($allNav, $n['data']);
        }
        $ne = $this->kernel->events('ui')->filter('ajax_paths', array());
        $allNav = array_merge($allNav, $ne);
        //___debug($allNav);
        $this->view->navigation = $allNav;
        return $this->view;
    }


}




















