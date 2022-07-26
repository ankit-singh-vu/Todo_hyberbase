<?php
\Kernel()->events('ui')->addFilter('load_navigation', 'module_admin_dashboard', function ($navigation) {
    //___debug($navigation);

    if ($navigation['params']['type'] == 'admin-left-navigation') {
        $navigation['data']['dashboard'] = array(
            'label' => 'Dashboard',
            'path'  => '/admin_dashboard',
            'icon'  => 'glyphicon glyphicon-dashboard',
            'weight' => 100
        );
        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_customer') == true) {
            $navigation['data']['tenant'] = array(
                'label' => 'Customers',
                'path'  => '/admin_tenant',
                'icon'  => 'fa fa-users',
                'weight' => 200,
                'additional_routes' => array('/admin_tenant/:id')
            );
        }
        /*
        $navigation['data']['subscription'] = array(
            'label' => 'Subscriptions',
            'path'  => '/admin_subscription',
            'icon'  => 'fa fa-certificate',
            'weight' => 250
        );*/
        /*
        $navigation['data']['invoice'] = array(
            'label' => 'Invoices & Billing',
            'path'  => '/admin_invoice',
            'icon'  => 'fa fa-credit-card',
            'weight' => 500
        );*/
        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_plan') == true) {
            $navigation['data']['plan'] = array(
                'label' => 'Plans & Offerings',
                'path' => '/admin_package',
                'icon' => 'glyphicon glyphicon-usd',
                'weight' => 555
            );
        }
        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_course') == true) {
            $navigation['data']['courses'] = array(
                'label' => 'Courses',
                'path' => '/admin_course',
                'icon' => 'fa fa-book',
                'weight' => 560,
                'additional_routes' => array('/admin_course/:id')
            );
        }
        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_service') == true) {
            $navigation['data']['services'] = array(
                'label' => 'Services',
                'path' => '/admin_services',
                'icon' => 'fa fa-cogs',
                'weight' => 570
            );
        }

        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_ticket') == true) {
            $navigation['data']['ticket'] = array(
                'label' => 'Tickets',
                'path' => '/admin_ticket',
                'icon' => 'fa fa-ticket',
                'weight' => 580
            );
        }

        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_weekly_call') == true) {
            $navigation['data']['weekly_calls'] = array(
                'label' => 'Weekly Calls',
                'path' => '/admin_weeklycall',
                'icon' => 'fa fa-phone-square',
                'weight' => 585
            );
        }

        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_recorded_call') == true) {
            $navigation['data']['recorded_calls'] = array(
                'label' => 'Recorded Calls',
                'path' => '/admin_pastcall',
                'icon' => 'fa fa-file-video-o',
                'weight' => 590
            );
        }

        if (\Model\Permission::verify(\Model\Session::load_user()->user_id, 'can_view_tag') == true) {
            $navigation['data']['tag_management'] = array(
                'label' => 'Tag Management',
                'path' => '/admin_calltag',
                'icon' => 'fa fa-tasks',
                'weight' => 600
            );
        }

        $navigation['data']['settings'] = array(
            'label' => 'System Settings',
            'path' => '/admin_settings',
            'icon' => 'fa fa-barcode',
            'weight' => 700
        );
    }


    if ($navigation['params']['type'] == 'admin-user-navigation' && $navigation['params']['segment'] == 'user') {
        $navigation['data']['profile'] = array(
            'label' => 'My Profile',
            'path'  => '/profile',
            'icon'  => 'glyphicon glyphicon-user',
            'weight' => 10
        );
    }
    if ($navigation['params']['type'] == 'admin-user-navigation' && $navigation['params']['segment'] == 'access') {
        $navigation['data']['logout'] = array(
            'label'     => 'Logout',
            'path'      => '/system/logout',
            'icon'      => 'fa fa-sign-out',
            'weight'    => 20,
            'ajax_load' => false
        );
    }

    if ($navigation['params']['type'] == 'admin-user-navigation' && $navigation['params']['segment'] == 'tenant') {
        // $navigation['data']['users'] = array(
        //     'label' => 'Staff & Permissions',
        //     'path' => '/admin_staff',
        //     //'route' => array(array(
        //     //    'controller'    => 'admin_staff'
        //     //), 'model_method'),
        //     'icon' => 'fa fa-users',
        //     'weight' => 10
        // );
        /*
        $navigation['data']['deployment'] = array(
            'label' => 'Infrastructure',
            'path' => '/admin_deployment',
            'icon' => 'fa fa-cubes',
            'weight' => 30
        );*/
        /*
        $navigation['data']['settings'] = array(
            'label' => 'System Settings',
            'path' => '/admin_settings',
            'icon' => 'glyphicon glyphicon-cog',
            'weight' => 20
        );
        */
    }

    return $navigation;
});
