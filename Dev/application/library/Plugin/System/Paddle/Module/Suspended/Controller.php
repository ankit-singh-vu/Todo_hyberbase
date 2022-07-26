<?php

namespace Module\Suspended;

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
    public function trialAction(System\Request $request)
    {
        $tenent = \Model\Session::load_user()->user->get_current_web_tenant();
        $now = date('U');
        $expiry = $tenent->create_ts + (60*60*24*15);
        $subscription_count = \Model\Subscription::count_by_tenant_id($tenent->id);
        if ($subscription_count != 0) {
            $this->kernel->redirect('/');
        }
        $active_subscription_count = \Model\Subscription::count_by_tenant_id_and_status($tenent->id, STATUS_ACTIVE);
        if($active_subscription_count == 0) {

            if($expiry > $now) {
                $this->kernel->redirect('/');
            }
        }

        $this->view->account_close_in = '90 days from the date of suspension'; //$this->kernel->secondsToTime($expiry + (60*60*24*90));

        $this->kernel->disableLayout();
        return $this->view;
    }

}




















