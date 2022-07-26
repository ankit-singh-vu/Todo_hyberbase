<?php

namespace Module\Validation;

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
    protected $skip_user_email_validation_check = true;
    protected $skip_authentication = array('email');

    public function indexAction(System\Request $request)
    {
        if($this->user->is_tag_present(USER_STATUS_EMAIL_VERIFICATION_REQUIRED) == false) {
            $this->kernel->redirect('/');
        }
        $this->kernel->disableLayout();
        return $this->view;
    }

    public function statusAction(System\Request $request)
    {
        $return = array();
        if($this->user->is_tag_present(USER_STATUS_EMAIL_VERIFICATION_REQUIRED) == true) {
            $return['validated'] = false;
        } else {
            $return['validated'] = true;
        }
        return $return;
    }

    public function resendAction(System\Request $request)
    {
        $this->kernel->load_plugin('System_EmailValidation')->send_validation_email($this->user);
        $this->kernel->redirect('/');
    }

    public function emailAction(System\Request $request)
    {
        $ota = \Model\Otalink::find_by_key_and_action($request->get('key'), 'user_email_validation');
        if(!$ota) {
            $this->kernel->redirect('/login?verificatonsuccess=0');
        }
        $now = date('U');
        if($ota->expiry < $now) {
            //Link Expired
            $ota->delete();
            $this->kernel->redirect('/login?verificatonsuccess=0');
        }
        $otaData = json_decode($ota->data, true);
        try {
            $user = \Model\User::find($otaData['user_id']);
        } catch(\Exception $e) {
            //___debug($e);
            $ota->delete();
            $this->kernel->redirect('/login?verificatonsuccess=0');
        }
        $user->rm_tag(USER_STATUS_EMAIL_VERIFICATION_REQUIRED);
        $ota->delete();
        $this->kernel->redirect('/login?verificatonsuccess=1');
    }


}




















