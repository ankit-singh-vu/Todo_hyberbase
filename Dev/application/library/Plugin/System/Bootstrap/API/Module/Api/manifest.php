<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_api', function($navigation)
{
    if($navigation['params']['type'] == 'profile-navigation')
    {
        // $navigation['data']['profile_api'] = array(
        //     'label'     => 'API Settings',
        //     'path'      => '/api',
        //     'icon'      => 'fa fa-exchange',
        //     'weight'    => 20,
        //     'modal'     => true,
        // );
    }

    return $navigation;
});

\Kernel()->events('ui')->addFilter('ajax_navigation', 'module_api', function($navigation) {
    if (!isset($navigation['profile-navigation'])) {
        $navigation['profile-navigation'] = array('type' => 'profile-navigation');
    }
    return $navigation;
});

//___debug(\Kernel()->config('system.path.lib') . '/Plugin/System/Bootstrap/Module/Profile');