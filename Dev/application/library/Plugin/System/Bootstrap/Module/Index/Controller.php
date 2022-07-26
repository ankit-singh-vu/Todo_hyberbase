<?php

namespace Module\Index;

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
    protected $authentication = false;
    //protected $register_callbacks = false;
    //protected $skip_authentication = array('initexec');

    public function indexAction(System\Request $request)
    {
       set_time_limit(0);

       //___debug($_SERVER);

       $this->view->helper('head')->script('script/route.js');
       return $this->view;
    }

    public function testpocAction(System\Request $request)
    {
        /** /
        try {
            $session = \App\Account::create(array(
                'email'     => 'dc@mclogics.com',
                'uname'     => 'Dyutiman Chakraborty',
                'password'  => 'b2W1902m',
                'plan'      => 1
            ));
        } catch (\Exception $e) {
            ___debug($e);
        }
        ___debug($session);
        /**/


        /** /
        $plan = \Model\Plan::create(array(
            "uuid"        => gen_uuid(),
            "name"        => "Basic Plan",
            "cost"        => "3500",
            "currency"    => "usd",
            "trial"       => 0,
            "pg_data"     => json_encode(array()),
            "status"      => STATUS_ACTIVE,
        ));
        ___debug($plan);
        /**/


        /** /
        try {
            $plan = \Model\Plan::find(1);
        } catch (\Exception $e) {
            ___debug($e);
        }
        $plan->set_tag(array(PLAN_RECURRING_INTERVAL_MONTHLY, PLAN_TYPE_DEMO));
        $plan->rm_tag(PLAN_RECURRING_INTERVAL_MONTHLY);
        ___debug($plan->is_tag_present(PLAN_TYPE_DEMO));
        /**/




        /** /
        try {
            $plan = \Model\Plan::find(1);
        } catch (\Exception $e) {
            ___debug($e);
        }
        //$plan->set_variable('created_by', 'Dyutiman Chakraborty');
        //$plan->set_variable('cost_structure', array(
        //    'monthly'   => 3500,
        //    'annual'    => 35000
        //));
        //___debug($plan->get_variable('cost_structure'));
        $plan->rm_variable('created_by');
        ___debug($plan);
        /**/




        /** /
        try {
            $mail = \Model\Notification::send('Dyutiman Chakraborty <dc@mclogics.com>', array(
                'subject'   => 'Checking email service',
                'template'  => 'validate.user.email',
                'variables' => array(
                    'user_name'                 => 'Priyanka',
                    'user_email_confirm_link'   => 'https://panel.wpstack.io/validate_email/5c3fd299-c9e5-4d2e-8657-6fda5afd281c?key=123456789'
                )
            ));
        } catch(\Exception $e) {
            ___debug($e);
        }
        ___debug($mail);
        /**/


        ___debug('READY FOR TESTING');

    }

}




















