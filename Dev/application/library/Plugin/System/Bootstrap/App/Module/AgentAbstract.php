<?php

namespace App\Module;

use App,
    System;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
abstract class AgentAbstract extends System\Module\ControllerAbstract
{
    protected $authentication = true;
    protected $skip_authentication = array();

    protected $user = null;
    protected $access = null;
    protected $session = null;
    protected $server = null;

    public function __construct(System\Kernel $kernel)
    {
        parent::__construct($kernel);
        if($this->authentication == true && !in_array($this->kernel->request()->param('action'), $this->skip_authentication)) {
            if($this->authentication() == true) {
                \Kernel()->events('app')->trigger('user_authenticated', array($this->user, ACCESS_TYPE_AGENT));
            } else {
                //Block Access
                header('Content-Type: application/json');
                echo json_encode(array(
                    'error' => 'access_denied'
                ));
                exit;
            }
        }
    }

    protected function authentication()
    {
        $agent_access = $this->kernel->request()->header('x-hb-rpc-access-token');

    }
}




















