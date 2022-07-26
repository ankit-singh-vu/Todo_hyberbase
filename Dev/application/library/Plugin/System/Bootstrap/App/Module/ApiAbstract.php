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
abstract class ApiAbstract extends System\Module\ControllerAbstract
{
    protected $authentication = true;
    protected $skip_authentication = array();

    protected $user = null;
    protected $access = null;
    protected $session = null;

    public function __construct(System\Kernel $kernel)
    {
        parent::__construct($kernel);
        if($this->authentication == true && !in_array($this->kernel->request()->param('action'), $this->skip_authentication)) {
            if($this->authentication() == true) {
                \Kernel()->events('app')->trigger('user_authenticated', array($this->user, ACCESS_TYPE_API));
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
        $api_access = $this->kernel->request()->header('x-hb-api-access-token');
        if($api_access === 0) {
            return false;
        }
        $this->session  = \Model\Session::load_user($api_access);
        if($this->session instanceof \Model\Session) {
            $this->user = $this->session->user;
            $this->access = $this->session->access;
            if($this->access->access_type != ACCESS_TYPE_API) {
                return false;
            }
            $this->access->last_access = date('U');
            $this->access->save();
            return true;
        } else {
            return false;
        }
    }
}




















