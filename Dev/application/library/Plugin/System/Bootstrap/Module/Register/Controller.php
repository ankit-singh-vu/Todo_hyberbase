<?php

namespace Module\Register;

use App,
    System,
    Model;


/**
 * Index Module
 *
 * Extends from base Application controller so custom functionality can be added easily
 * lib/App/Module/ControllerAbstract
 */
class Controller extends \System\Module\ControllerAbstract
{
    public function indexAction(System\Request $request)
    {
        if($this->kernel->config('app.registration.enabled', false) == true
            && $this->kernel->config('app.registration.form', false) == true ) {

            $registration_key = gen_uuid();
            $this->view->registration_key = $registration_key;
            $this->view->registration_signature = md5($registration_key.$this->kernel->config('app.registration.salt'));

            $this->kernel->disableLayout();
            return $this->view;
        }
        return false;
    }

    public function postMethod(System\Request $request)
    {
        if($this->kernel->config('app.registration.enabled', false) == false) {
            return false;
        }
        $key = $request->post('key');
        $signature = $request->post('signature');
        $salt = $this->kernel->config('app.registration.salt');
        if(md5($key.$salt) != $signature) {
            return false;
        }
        try {
            /*
            $plan = \Model\Plan::find_by_uuid($request->post('plan'));
            if(!$plan) {
                throw new \Exception('Plan not found', 806);
            }*/
            $fullname = $request->post('firstname') . ' ' . $request->post('lasttname');

            if($request->post('password') != $request->post('repassword')) {
                throw new \Exception('Password do not match', 807);
            }
            $session = \App\Account::create(array(
                'email' => $request->post('email'),
                'uname' => $fullname,
                'password' => $request->post('password'),
                'sent_mail'=> 1
            ));
        } catch (\Exception $e) {
            if($request->isAjax()) {
                return array(
                    'error' => $e->getMessage()
                );
            } else {
                $registration_key = gen_uuid();
                $this->view->registration_key = $registration_key;
                $this->view->registration_signature = md5($registration_key.$this->kernel->config('app.registration.salt'));

                $this->kernel->disableLayout();
                $this->view->file('index', 'html');
                $this->view->error = $e->getMessage();
                return $this->view;
            }
        }
        if($request->isAjax()) {
            return array(
                'token' => $session->token
            );
        } else {
            $this->kernel->redirect('/login/direct?token=' . $session->token);
        }
    }

}




















