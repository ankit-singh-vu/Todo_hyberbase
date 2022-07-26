<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_user', function($navigation)
{
    if($navigation['params']['type'] == 'admin-user-navigation' && $navigation['params']['segment'] == 'tenant') {
        if(\Model\User::find(\Model\Session::load_user()->user_id)->user_type == SYSTEM_USER_TYPE_ADMIN){
            $navigation['data']['users'] = array(
                'label' => 'Users & Permissions',
                'path' => '/admin_staff',
                'icon' => 'fa fa-users',
                'weight' => 10
            );
        }
    }
    return $navigation;
});
