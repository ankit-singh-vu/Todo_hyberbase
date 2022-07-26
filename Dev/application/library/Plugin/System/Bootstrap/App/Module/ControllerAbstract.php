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
abstract class ControllerAbstract extends System\Module\ControllerAbstract
{
    protected $authentication = true;
    protected $skip_authentication = array();

    protected $user = null;
    protected $access = null;
    protected $session = null;

    protected $callToActions = array(
        'user' => array(
            'create_service' => array(
                'label' => 'Create App',
                'path'  => '/create',
                'icon'  => 'fa fa-plus',
                'weight' => 50,
                'btn-type'  => 'wpshq-primary'
            )
        )
    );

    public function __construct(System\Kernel $kernel)
    {
        parent::__construct($kernel);
        if ($this->authentication == true && !in_array($this->kernel->request()->param('action'), $this->skip_authentication)) {
            if ($this->authentication() == true) {
                \Kernel()->events('app')->trigger('user_authenticated', array($this->user, ACCESS_TYPE_WEB));
            }
        }
        $this->view->helper('head')->title($kernel->config('title'));
        $this->addGlobalCSS();
        $this->addGlobalJS();
        $this->loadCallToAction();
    }

    protected function loadCallToAction()
    {
        /*
        $this->kernel->events('ui')->addFilter('load_navigation', 'boostrap_controller_abstract', function($navigation) {
            if($navigation['params']['type'] == 'user-call-to-action') {
                $navigation['data'] = array_merge($this->callToActions['user'], $navigation['data']);
            }
            return $navigation;
        });
        */
    }


    protected function addGlobalCSS()
    {
        foreach ($this->kernel->events('ui')->filter('load_css', array()) as $file => $style) {
            if (!isset($style['options'])) {
                $style['options'] = array();
            }
            if (!isset($style['condition'])) {
                $style['condition'] = false;
            }
            $this->view->helper('head')->stylesheet($file, $style['options'], $style['condition']);
        }
    }

    protected function addGlobalJS()
    {
        foreach ($this->kernel->events('ui')->filter('load_script', array()) as $file => $option) {
            $this->view->helper('head')->script($file, $option);
        }
    }

    /**
     * Authenticates access
     *
     * @return bool
     * @throws \Exception
     */
    protected function authentication()
    {
        if (isset($_COOKIE["access_token"]) && !isset($_POST['username'])) {
            $this->session  = \Model\Session::load_user();
            if ($this->session instanceof \Model\Session) {
                $this->user = $this->session->user;
                // check suspended
                if (\Model\User::find($this->session->user_id)->status == STATUS_SUSPENDED) {
                    setcookie("access_token", 0, time() - (60 * 60 * 24), "/", $this->kernel->config('cookie_domain'), 1);
                    $this->requestLogin(3);
                    return false;
                }
                $this->access = $this->session->access;
                if ($this->access->tenant == 0) {
                    $this->access->tenant = $this->user->c_tenant;
                }
                $this->access->last_access = date('U');
                $this->access->save();
                return true;
            } else {
                setcookie("access_token", 0, time() - (60 * 60 * 24), "/", $this->kernel->config('cookie_domain'), 1);
                $this->requestLogin(2);
                return false;
            }
        } elseif (isset($_POST['username']) && isset($_POST['password'])) {
            $access = \Model\Access::find_by_access_type_and_access_key_and_access_secret(
                ACCESS_TYPE_WEB,
                $this->kernel->request()->post('username'),
                md5($this->kernel->request()->post('password'))
            );
            $access = $this->kernel->events('system')->filter('authenticated_access_loaded', $access);
            //___debug($access);
            if ($access instanceof \Model\Access) {
                // check suspended
                if (\Model\User::find($access->user_id)->status == STATUS_SUSPENDED) {
                    $this->requestLogin(3);
                    return false;
                }
                $this->session = \Model\Session::register($access);
                $this->user = $this->session->user;
                $this->access = $this->session->access;
                if ($this->access->tenant != $this->user->c_tenant) {
                    $this->access->tenant = $this->user->c_tenant;
                }
                $this->access->last_access = date('U');
                $this->access->save();
                setcookie("access_token", $this->session->token, time() + 60 * 60 * 24 * 365, "/", $this->kernel->config('cookie_domain'), 1);
                $this->kernel->redirect('/');
                return true;
            } else {
                $this->requestLogin(1);
                return false;
            }
        }
        $this->requestLogin();
        return false;
    }

    /**
     * @param null $err
     * @return array|bool
     */
    protected function requestLogin($err = null)
    {
        if ($this->kernel->request()->isAjax()) {
            header("Content-type:application/json");
            echo json_encode(array(
                'error' => 'session_expired',
                'code'  => $err
            ));
            exit;
        } else {
            $this->kernel->redirect('/login/' . ($err != null ? '?err=' . $err : ''));
            return false;
        }
    }

    protected function access_denied($err = null)
    {
        if ($err == null) {
            $err = 'You do not have the permission to access this section. <br/> If you think this is an error, please contact support';
        }
        return '<div style="text-align:center;margin-top:5%;color:#cc0000"><h4><i class="fa fa-warning"></i> Access Denied</h4><p>' . $err . '</p></div>';
    }
}
