<?php

namespace Module\Login\Reset;

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
    /**
     * Forget password
     *
     * @param System\Request $request
     * @return System\View\Template
     */
    public function postMethod(System\Request $request)
    {
        //___debug($_POST);
        $password = $request->post('newpassword');
        $repassword = $request->post('renewpassword');
        if($password != $repassword) {
            $this->kernel->redirect('/login/reset?key='.$request->post('key').'&err=1'); //password do not match
        }

        $ota = \Model\Otalink::find_by_key_and_action($request->post('key'), 'reset_password');
        if(!$ota) {
            $this->kernel->redirect('/login/reset?key='.$request->post('key').'&err=2'); //OTA profile do not exist
        }
        $now = date('U');
        if($ota->expiry < $now) {
            $this->kernel->redirect('/login/reset?key='.$request->post('key').'&err=3'); //OTA profile has expired
        }
        $otaData = json_decode($ota->data, true);
        try {
            $user = \Model\User::find($otaData['user_id']);
        } catch(\Exception $e) {
            $this->kernel->redirect('/login/reset?key='.$request->post('key').'&err=4'); //User not found
        }

        $access = \Model\Access::find_by_user_id_and_access_type($user->id, ACCESS_TYPE_WEB);
        if(!$access) {
            $this->kernel->redirect('/login/reset?key='.$request->post('key').'&err=5'); //User WEB access profile not found
        }
        $access->access_secret = md5($password);
        $access->save();
        $ota->delete();
        $this->kernel->redirect('/login?passreset=1');
    }

}




















