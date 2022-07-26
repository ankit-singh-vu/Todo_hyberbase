<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_billing', function($navigation)
{
    if($navigation['params']['type'] == 'user-navigation' && $navigation['params']['segment'] == 'tenant')
    {
        // $navigation['data']['billing'] = array(
        //     'label' => 'Billing & Invoices',
        //     'path'  => '/billing',
        //     'icon'  => 'fa fa-credit-card',
        //     'weight' => 50
        // );
    }
    return $navigation;
});
