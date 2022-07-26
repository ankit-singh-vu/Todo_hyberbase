<?php

// Configuration
$app = array();
$app['name']                = getenv('APPLICATION_NAME');                                  // Name of the Application
$app['version']             = getenv('APPLICATION_VERSION');
$app['title']               = getenv('APPLICATION_NAME');                                 // Web Browser Titkle for the Application
$app['brand_domain']        = getenv('APPLICATION_DOMAIN');
$app['cookie_domain']       = getenv('APPLICATION_DOMAIN');
$app['api_cookie_domain']   = 'api.' . getenv('APPLICATION_DOMAIN');
$app['url']['rewrite']      = true;
$app['debug']               = false; //getenv('DEBUG_MODE')==1;
$app['mode']['development'] = 1; //getenv('DEVELOPMENT_MODE')==1;
$app['session']['lifetime'] = 28000;
$app['identity']            = getenv('APPLICATION_NAME');                                 // Mail identity of the application
$app['protocol']            = getenv('HTTP_PROTOCOL');
$app['currency']        = array(
    'usd'   => array(
        'symbol'    => '$',
        'abbri'     => 'USD',
        'name'      => 'US Dollar'
    ),
    'cad'   => array(
        'symbol'    => '$',
        'abbri'     => 'CAD',
        'name'      => 'Canadian Dollar'
    )
);

$app['QUICKBOOK_CLIENT_ID']         = getenv('QUICKBOOK_CLIENT_ID');
$app['QUICKBOOK_SECRET']            = getenv('QUICKBOOK_SECRET');
$app['QUICKBOOK_REDIRECT_URL']      = getenv('QUICKBOOK_REDIRECT_URL');

$app['attachment']['path'] = '/opt/attachments';
$app['profile_picture']['path'] = '/opt/profile_picture';
$app['ticket_image']['path'] = '/opt/ticket_image';
$app['past_calls']['path'] = '/opt/past_calls';

$app['support_mail'] = getenv('SUPPORT_MAIL');

$app['billing']['intervals'] = array(
    'setup fees'   => 'Setup Fee',
    'daily'        => 'Daily',
    'weekly'       => 'Weekly',
    'monthly'      => 'Monthly',
    'quarterly'    => 'Quarterly',
    'half-yearly'  => 'Half-Yearly',
    'Yearly'       => 'Yearly'
);

$app['com']['email']['support'] = getenv('APPLICATION_SUPPORT_EMAIL');


$app['i18n'] = array(                        // DEFAULT LOCAL SETTINGS
    'charset'       => 'UTF-8',
    'language'      => 'en_US',
    'timezone'      => getenv('APPLICATION_TIME_ZONE'),
    'date_format'   => 'M d, Y',
    'time_format'   => 'H:i'
);

$app['php']['cli']['exec']              = '/usr/bin/php';
$app['table']['pagination']['perpage']  = 25;

$app['registration'] = array(
    'enabled'   => true,
    'form'      => true,
    'salt'      => getenv('REGISTRATION_SALT')
);


// Load Required Plugins
$app['plugins'] = array(

    'System\DB',
    'System\DB\Version',
    'System\DB\Attributes',
    'System\Bootstrap',
    'System\Bootstrap\Backend',
    'System\Bootstrap\API',
    //'System\Isolation',
    'System\Layout',
    'System\Job',
    'System\Notification',
    //'System\Socket',
    'System\EmailValidation',
    'System\Preference',
    'System\Form',

);

$app['plugin']['system']['bootstrap'] = array(
    'dashboard' => array(
        'layout'    => 'app.dashboard.html.php',
    ),
    'landing_page'  => 'dashboard',
    'logo'  => array(
        //'full'  => null,
        'full'  => '<span style="color:#555;">Advisor<span style="font-weight: bold">LEARN</span></span>',
        //'full'  => '<img src="/assets/brand/logo.png" style="width:150px;" border="0"/>',
        //'short'   => null
        'short' => '<span style="color:#555;">AL</span>'
        //'short' => '<div style="width:33px;overflow:hidden;margin-left:6px;"><img src="/assets/brand/logo.png" style="width:150px;" border="0"/></div>',
    )
);

$app['plugin']['system']['bootstrap']['api'] = array(
    'allow_api_credential_generation'       => true,
    'max_api_account_per_user_per_tenant'   => 25
);

$app['plugin']['system']['bootstrap']['backend'] = array(
    'dashboard' => array(
        'layout'    => 'app.admin.dashboard.html.php',
    ),
);


$app['plugin']['system']['db'] = array(
    'connections'  => array(
        'application' => 'mysql://root:' . getenv('MYSQL_ROOT_PASSWORD') . '@pxc/' . getenv('MYSQL_DATABASE'),
    ),
    'default_connection' => 'application'
);

$app['users'] = array(
    'super'         => array(
        2
    ),
    'permissions'   => array(

        'can_view_customer' => array(
            'label'         => 'Can view customer menu',
            'description'   => 'This gives permission to view the customer details',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_invite_customer' => array(
            'label'         => 'Can invite customer',
            'description'   => 'This gives permission to invite customers through mail',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_add_customer' => array(
            'label'         => 'Can add customer',
            'description'   => 'This gives permission add customers',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_create_subscription' => array(
            'label'         => 'Can create subscription',
            'description'   => 'This gives permission to create subscription for customers',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),
        'can_suspend_subscription' => array(
            'label'         => 'Can suspend subscription',
            'description'   => 'This gives permission to suspend an existing subscription for customers',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_edit_invoice' => array(
            'label'         => 'Can edit invoice',
            'description'   => 'This gives permission to edit invoices',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_send_payment_link' => array(
            'label'         => 'Can send payment link',
            'description'   => 'This gives permission to send link to customers for payment',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_add_direct_payment' => array(
            'label'         => 'Can add direct payment',
            'description'   => 'This gives permission to add direct payment using debit/credit card',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_download_invoice' => array(
            'label'         => 'Can download invoice',
            'description'   => 'This gives permission to download paid or pending invoices',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_invoice_logs' => array(
            'label'         => 'Can view invoice logs',
            'description'   => 'This gives permission to view invoice logs',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_cancel_invoice' => array(
            'label'         => 'Can cancel an invoice',
            'description'   => 'This gives permission to cancel an upcoming/not paid invoice',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_update_subscription_content_access_settings' => array(
            'label'         => 'Can update content access settings',
            'description'   => 'This gives permission to update customer content access settings',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_reset_subscription_content_access_settings' => array(
            'label'         => 'Can reset content access settings',
            'description'   => 'This gives permission to reset customer content access settings',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_block_subscription_content_access' => array(
            'label'         => 'Can block content access settings',
            'description'   => 'This gives permission to block customer content access settings',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_send_docusign' => array(
            'label'         => 'Can send docusign documents',
            'description'   => 'This gives permission to send documents to customers for signing',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_download_docusign' => array(
            'label'         => 'Can download docusign documents',
            'description'   => 'This gives permission to download sent documents to customers',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_plan' => array(
            'label'         => 'Can view plans & offerings menu',
            'description'   => 'This gives permission to view plans & offering details',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_create_plan' => array(
            'label'         => 'Can create new plan',
            'description'   => 'This gives permission to create new plans',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_manage_plan' => array(
            'label'         => 'Can manage plans',
            'description'   => 'This gives permission to manage and update previously created plans',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_delete_plan' => array(
            'label'         => 'Can delete plans',
            'description'   => 'This gives permission to delete plans',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_course' => array(
            'label'         => 'Can view course menu',
            'description'   => 'This gives permission to view courses details',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_create_course' => array(
            'label'         => 'Can create course',
            'description'   => 'This gives permission to create new course',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_manage_course' => array(
            'label'         => 'Can manage courses',
            'description'   => 'This gives permission to manage and update previously created course',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_service' => array(
            'label'         => 'Can view service menu',
            'description'   => 'This gives permission to view service details',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_ticket' => array(
            'label'         => 'Can view tickets menu',
            'description'   => 'This gives permission to view the ticket details',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_close_ticket' => array(
            'label'         => 'Can close tickets menu',
            'description'   => 'This gives permission to close the raised ticket',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_weekly_call' => array(
            'label'         => 'Can view weekly call menu',
            'description'   => 'This gives permission to view the weekly call menu',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_schedule_weekly_call' => array(
            'label'         => 'Can schedule weekly call',
            'description'   => 'This gives permission to schedule weekly calls',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_recorded_call' => array(
            'label'         => 'Can view recorded call menu',
            'description'   => 'This gives permission to view the recorded call menu',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_upload_recorded_call' => array(
            'label'         => 'Can upload recorded call',
            'description'   => 'This gives permission to upload recorded calls',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_delete_recorded_call' => array(
            'label'         => 'Can delete recorded call',
            'description'   => 'This gives permission to delete recorded calls',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_view_tag' => array(
            'label'         => 'Can view tag manage menu',
            'description'   => 'This gives permission to view the tag manage menu',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_add_tag' => array(
            'label'         => 'Can upload tag',
            'description'   => 'This gives permission to upload tags',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_delete_tag' => array(
            'label'         => 'Can delete tag',
            'description'   => 'This gives permission to delete tags',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

        'can_manage_system_defaults' => array(
            'label'         => 'Can manage system settings',
            'description'   => 'This gives permission to manage system settings',
            'value'         => array(
                'type'          => 'switch',
                'options'       => array(
                    'on'    => 1,
                    'off'   => 0
                )
            )
        ),

    )
);

$app['plugin']['system']['notification'] = array(
    'protocols' => array(
        'default'   => array(
            'host'      => getenv('SMTP_HOST'),
            'username'  => getenv('SMTP_USERNAME'),
            'password'  => getenv('SMTP_PASSWORD'),
            'secure'    => getenv('SMTP_SECURE'),
            'port'      => getenv('SMTP_PORT')
        )
    ),
    'template_location' => 'application/emails',
    'sender'            => getenv('SMTP_EMAIL_FROM'),
    'image_uri'         => array('../../public/assets/email/img', 'https://s3.amazonaws.com/wpstackhq-email/assets'),
    //'exceptions'        => array(
    //    'notify'                => true,
    //    'notification_list'     => @implode(',', getenv('APPLICATION_NOTIFICATION_LIST'))
    //)
);


$app['plugin']['system']['notification']['protocols']['support'] = $app['plugin']['system']['notification']['protocols']['default'];
