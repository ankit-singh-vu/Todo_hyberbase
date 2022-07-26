<?php

namespace Module\Login;

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
        // echo "hi";die;
        $this->kernel->disableLayout();
        return $this->view;
    }

    public function directAction(System\Request $request)
    {
        $session = \Model\Session::find_by_token($request->get('token'));
        if(!$session) {
            return false;
        }
        setcookie("access_token", $session->token, time()+60*60*24*365, "/", $_SERVER['HTTP_HOST'], 1);
        //___debug($_SERVER);
        $this->kernel->redirect('/');
    }

    public function forgotpassAction(System\Request $request)
    {
        $this->kernel->disableLayout();
        return $this->view;
    }

    public function signupAction(System\Request $request)
    {
        $this->kernel->disableLayout();
        return $this->view;
    }

    public function resetAction(System\Request $request)
    {
        $ota = \Model\Otalink::find_by_key_and_action($request->get('key'), 'reset_password');
        //___debug($ota);
        if(!$ota) {
            return false;
        }
        $now = date('U');
        if($ota->expiry < $now) {
            //Link Expired
            $ota->delete();
            return false;
        }
        $otaData = json_decode($ota->data, true);
        try {
            $user = \Model\User::find($otaData['user_id']);
        } catch(\Exception $e) {
            ___debug($e);
            $ota->delete();
            return false;
        }

        $this->view->key = $ota->key;
        $this->view->email = $user->email;
        $this->view->user = $user->first_name;

        $this->kernel->disableLayout();
        return $this->view;
    }

    /**
     * Forget password
     *
     * @param System\Request $request
     * @return System\View\Template
     */
    public function postMethod(System\Request $request)
    {
        try {
            $user = \Model\User::find_by_email($request->post('username'));
            if(!$user) {
                throw new \Exception('The given email address not registered with us. Please use a valid email address', 808);
            }
            if($user->status == STATUS_SUSPENDED) {
                throw new \Exception('This account is suspended', 808);
            }
            $link_key = gen_uuid() . '-' . gen_uuid();
            \Model\Otalink::create(array(
                "key"         => $link_key,
                "action"      => "reset_password",
                "expiry"      => date('U') + (60*60*24),
                "data"        => json_encode(array(
                    'user_id'       => $user->id,
                    'access_type'   => ACCESS_TYPE_WEB
                )),
            ));

            $this->kernel->notify(array(
                NOTIFICATION_TYPE_EMAIL => array(
                    'to'        => $user,
                    'subject'   => 'Reset your account password',
                    'template'  => 'reset.password.email',
                    'variables' => array(
                        'user_first_name'           => $user->first_name,
                        'user_password_reset_link'  => 'https://'.getenv('APPLICATION_DOMAIN').'/login/reset?key=' .$link_key
                    )
                )
            ));


        } catch(\Exception $e) {
            $this->kernel->disableLayout();
            $this->view->file('forgotpass', 'html');
            $this->view->error = $e->getMessage();
            return $this->view;
        }


        $this->kernel->disableLayout();
        return $this->view;
    }

}




















