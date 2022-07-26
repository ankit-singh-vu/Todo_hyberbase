<?php

// Configuration
$app = array();
$app['name']                = 'hyperBase';   				             // Name of the Application
$app['version']             = '1.0-rc1';
$app['title']               = 'HyerBase';			                     // Web Browser Titkle for the Application
$app['brand_domain']        = 'hb.local';
$app['cookie_domain']       = getenv('COOKIE_DOMAIN');
$app['api_cookie_domain']   = 'api.' .getenv('COOKIE_DOMAIN');
$app['url']['rewrite']      = true;
$app['debug'] = false;
$app['mode']['development'] = true;
$app['session']['lifetime'] = 28000;
$app['identity']            = 'HyerBase';    		                     // Mail identity of the application
$app['protocol']            = getenv('HTTP_PROTOCOL');

$app['com']['email']['support'] = 'support@hb.local';


$app['i18n'] = array(						// DEFAULT LOCAL SETTINGS
    'charset'       => 'UTF-8',
    'language'      => 'en_US',
    'timezone'      => 'Asia/Calcutta',
    'date_format'   => 'M d, Y',
    'time_format'   => 'H:i:s'
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
    'System\Bootstrap\API',
    'System\Layout',
    'System\Job',
    'System\Notification',
    'System\Socket',
    'System\EmailValidation',
    'System\Preference',
    'System\Form'
);


$app['plugin']['system']['bootstrap'] = array(
    'dashboard' => array(
        'layout'    => 'app.dashboard.html.php',
    ),
    'landing_page'  => 'dashboard',
    'logo'  => array(
        //'full'  => '<span style="color:#fff;font-weight: bold">StatusNGIN</span>',
        'full'  => '<img src="/assets/brand/webngin-logo.png" style="width:150px;" border="0"/>',
        //'short' => '<span style="color:#fff;">SN</span>'
        'short' => '<div style="width:33px;overflow:hidden;margin-left:6px;"><img src="/assets/brand/webngin-logo.png" style="width:150px;" border="0"/></div>',
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
        //'application' => 'mysql://root:'.getenv('MYSQL_ROOT_PASSWORD').'@pxc/'.getenv('MYSQL_DATABASE'),
        //'livezilla' => 'mysql://root:'.getenv('LIVEZILLA_MYSQL_ROOT_PASSWORD').'@'.getenv('LIVEZILLA_MYSQL_HOST').'/'.getenv('LIVEZILLA_MYSQL_DATABASE'),
    ),
    //'default_connection' => 'application'
);

$app['plugin']['system']['notification'] = array(
    'protocols' => array(
        'default'   => array(
            'host'      => getenv('DEFAULT_SMTP_HOST'),
            'username'  => getenv('DEFAULT_SMTP_USERNAME'),
            'password'  => getenv('DEFAULT_SMTP_PASSWORD'),
            'secure'    => getenv('DEFAULT_SMTP_SECURE'),
            'port'      => getenv('DEFAULT_SMTP_PORT')
        )
    ),
    'template_location' => 'application/emails',
    'sender'            => 'StatusNGIN <'.getenv('DEFAULT_SMTP_EMAIL_FROM').'>',
    'image_uri'         => array('../../public/assets/email/img', 'https://s3.amazonaws.com/wpstackhq-email/assets'),
    'exceptions'        => array(
        'notify'                => true,
        'notification_list'     => array('dc@mclogics.com')
    )
);

//$app['plugin']['system']['notification']['protocols']['support'] = $app['plugin']['system']['notification']['protocols']['default'];