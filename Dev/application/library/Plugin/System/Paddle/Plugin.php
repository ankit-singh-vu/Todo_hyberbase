<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */
namespace Plugin\System\Paddle;
use System, RuntimeException;

define('PLAN_TYPE_ACCOUNT_SUBSCRIPTION', 1);
define('PLAN_TYPE_ADDON', 2);

define('PLAN_ACCESS_PUBLIC', 1);
define('PLAN_ACCESS_PRIVATE', 2);

/**
 * Layout Plugin
 * Wraps layout template around content result from main dispatch loop
 */
class Plugin
{
    protected $kernel;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        //$kernel->loader()->registerNamespace('Model', __DIR__);
        $kernel->loader()->registerNamespace('Module', __DIR__);


        $kernel->events('ui')->addFilter('load_footer_content', 'plugin_paddle', function($footer_content) use($kernel) {
            $footer_content[] = process_template(file_get_contents(__DIR__ . '/paddle-script.html.php'), array(
                'vendor_id' => getenv('PADDLE_VENDOR_ID')
            ));
            return $footer_content;
        });

        $kernel->events('ui')->addFilter('payment_link', 'plugin_paddle', function($parameters) use($kernel) {

            //$plan = \Model\Plan::find($parameters['plan_id']);
            $resp  = '<a href="#!" class="paddle_button '. (isset($parameters['class'])?$parameters['class']:'') .'" ';
            if(isset($parameters['data'])) {
                foreach ($parameters['data'] as $key => $value) {
                    $resp.= ' data-'.$key.'="'.$value.'" ';
                }
            }
            $resp.= '>';
            $resp.= $parameters['label'] . '</a>';
            return $resp;
        });


        $kernel->events('app')->addFilter('load_plan_metric_types', 'plugin_paddle', function($parameters) use($kernel) {

            $parameters['site'] = array(
                'label'         => 'Site',
                'descriptions'  => 'Number of sites the respective plan adds to the account',
                'default'       => 0
            );

            $parameters['server'] = array(
                'label'         => 'Server',
                'descriptions'  => 'Number of servers the respective plan adds to the account',
                'default'       => 0
            );

            $parameters['storage'] = array(
                'label'         => 'Storage',
                'descriptions'  => 'Amount of storage in GB the respective plan adds to the account',
                'default'       => 0
            );

            return $parameters;

        });
        $kernel->events('ui')->addFilter('page_header_head_right_extend', 'plugin_paddle', function($parameters) use($kernel) {

            if($parameters['type'] == 'user') {
                $tenent = $parameters['user']->get_current_web_tenant();
                $active_subscription_count = \Model\Subscription::count_by_tenant_id_and_status($tenent->id, STATUS_ACTIVE);
                if($active_subscription_count == 0) {
                    $now = date('U');
                    $expiry = $tenent->create_ts + (60*60*24*15);
                    $days_left = $kernel->secondsToTime($expiry - $now);
                    $parameters['html'] = '<span style="color:#B22222;padding:16px;padding-bottom:0;">
                        Your trial will end in '. $days_left .'. <a class="add-payment-method" style="padding-right:0;padding-left:0;text-decoration:underline;" href="#">Click Here</a> to upgrade your account.
                    </span>';
                }

            }
            return $parameters;
        });

        $kernel->events('app')->bind('user_authenticated', 'plugin_paddle', function(\Model\User $user, $type=ACCESS_TYPE_WEB) use ($kernel) {
            if(!$user->is_tag_present(SYSTEM_USER_TYPE_STAFF) && $type == ACCESS_TYPE_WEB) {
                $tenent = $user->get_current_web_tenant();
                $active_subscription_count = \Model\Subscription::count_by_tenant_id_and_status($tenent->id, STATUS_ACTIVE);
                if($active_subscription_count == 0) {
                    $request = \Kernel()->request()->params();
                    //___debug($request);
                    $now = date('U');
                    $expiry = $tenent->create_ts + (60*60*24*15);
                    if($now > $expiry) {
                        if (!in_array($request['module'], array('suspended', 'system'))) {
                            $kernel->redirect('/suspended/trial');
                        }
                    }
                }
            }
        });

        $this->kernel = $kernel;
    }

}