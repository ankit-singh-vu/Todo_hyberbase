<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_login', function($navigation)
{
    if($navigation['params']['type'] == 'user-navigation' && $navigation['params']['segment'] == 'access')
    {
        $navigation['data']['logout'] = array(
            'label'     => 'Logout',
            'path'      => '/system/logout',
            'icon'      => 'fa fa-sign-out',
            'weight'    => 20,
            'ajax_load' => false
        );
    }
    return $navigation;
});
