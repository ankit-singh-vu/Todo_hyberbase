<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_dashboard', function($navigation)
{
    if($navigation['params']['type'] == 'left-navigation')
    {
        /*
        $navigation['data']['dashboard'] = array(
            'label' => 'Dashboard',
            'path'  => '/dashboard',
            'icon'  => 'glyphicon glyphicon-dashboard',
            'weight' => 10
        );*/
    }
    return $navigation;
});
