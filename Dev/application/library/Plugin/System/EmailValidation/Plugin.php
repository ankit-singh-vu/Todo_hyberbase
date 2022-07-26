<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\EmailValidation;
use System, RuntimeException;

/**
 * EmailValidation Plugin
 *
 * Forces an user to validate his/her email address.
 */
class Plugin
{
    protected $kernel;
    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $this->kernel = $kernel;
        $kernel->loader()->registerNamespace('Module', __DIR__);
        $kernel->events('app')->bind('account_created', 'plugin_email_validation', function(\Model\Tenant $tenant, \Model\User $user) {
            $this->send_validation_email($user);
        });

        $kernel->events('app')->bind('user_authenticated', 'plugin_email_validation', function(\Model\User $user, $type=ACCESS_TYPE_WEB) use ($kernel) {
            if($type == ACCESS_TYPE_WEB) {
                if ($user->is_tag_present(USER_STATUS_EMAIL_VERIFICATION_REQUIRED) == true &&
                    $kernel->request()->param('module') != 'validation'
                ) {
                    $kernel->redirect('/validation');
                }
            }
        });
    }

    public function send_validation_email(\Model\User $user)
    {
        $user->set_tag(USER_STATUS_EMAIL_VERIFICATION_REQUIRED);
        $link_key = gen_uuid() . '-' . gen_uuid();
        $otaLink = \Model\Otalink::create(array(
            "key"         => $link_key,
            "action"      => "user_email_validation",
            "expiry"      => date('U') + (60*60*24),
            "data"        => json_encode(array(
                'user_id'       => $user->id,
                'access_type'   => ACCESS_TYPE_WEB
            )),
        ));

        try {
            $this->kernel->notify(array(
                NOTIFICATION_TYPE_EMAIL => array(
                    'to'        => $user,
                    'subject'   => 'Email address confirmation request',
                    'template'  => 'validate.user.email',
                    'variables' => array(
                        'user_first_name'          => $user->first_name,
                        'user_email_confirm_link'  => 'https://'.$this->kernel->config('app.cookie_domain').'/validation/email?key=' .$link_key
                    )
                )
            ));


        } catch(\Exception $e) {
            $this->kernel->notify_exception($e);
        }

    }

}