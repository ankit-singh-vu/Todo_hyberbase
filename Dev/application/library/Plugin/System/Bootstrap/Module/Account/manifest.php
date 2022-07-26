<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_account', function($navigation)
{
    if($navigation['params']['type'] == 'user-navigation' && $navigation['params']['segment'] == 'tenant') {
        $navigation['data']['users'] = array(
            'label' => 'Staff & Permissions',
            'path' => '/admin_staff',
            //'route' => array(array(
            //    'controller'    => 'admin_staff'
            //), 'model_method'),
            'icon' => 'fa fa-users',
            'weight' => 10
        );
        // $navigation['data']['account'] = array(
        //     'label' => 'Account Settings',
        //     'path'  => '/account',
        //     'icon'  => 'fa fa-cogs',
        //     'weight' => 45,
        //     'activate_links' => array('/account/index')
        // );
    }
    return $navigation;
});
