<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_profile', function($navigation)
{
    if($navigation['params']['type'] == 'user-navigation' && $navigation['params']['segment'] == 'user')
    {
        $navigation['data']['profile'] = array(
            'label' => 'My Profile',
            'path'  => '/profile',
            'icon'  => 'glyphicon glyphicon-user',
            'weight' => 10
        );
    }
    if($navigation['params']['type'] == 'user-navigation' && $navigation['params']['segment'] == 'access')
    {
        /*
        $navigation['data']['switch_account'] = array(
            'label'     => 'Switch Account',
            'path'      => '/profile/switch',
            'icon'      => 'fa fa-exchange',
            'weight'    => 10,
            'modal'     => true,
        );*/
    }

    if($navigation['params']['type'] == 'profile-navigation')
    {
        $navigation['data']['profile'] = array(
            'label'     => 'Profile Overview',
            'path'      => '/profile',
            'icon'      => 'fa fa-exchange',
            'weight'    => 10,
            'modal'     => true,
        );
    }

    return $navigation;
});
