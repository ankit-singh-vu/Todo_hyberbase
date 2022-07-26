<?php

/*
\Kernel()->events('system')->addFilter('load_user_permission', 'application_bootstrap', function($permissions) {
    $permissions['can_add_user'] = array(
        'label'     => 'Can invite users to this account',
        'default'   => false,
        'callback'  => function(\Model\User $user, \System\Request $request, $state) {
            return $state;
        }
    );
    return $permissions;
});
*/

/*
\Kernel()->events('system')->addFilter('enforce_user_permission', 'application_bootstrap', function($permissions) {
    $permissions['module_name'] = array(
        'defaults'    => array(
            HTTP_METHOD_ALL => 'can_add_user'
        ),
        'actions' => array(
            'action_name' => 'can_add_user'
        )
    );
    return $permissions;
});
*/

/*
\Kernel()->events('cron')->addFilter('interval', 'application_bootstrap', function($interval) {
    if(!isset($interval['10_MINUTES'])) {
        $interval['10_MINUTES'] = 60*10;
    }
    return $interval;
});
*/

/*
\Kernel()->events('cron')->bind('run', 'application_bootstrap', function(\Model\Cronlog $cron) {

    if($cron->ctype == \Plugin\System\Cron\Plugin::interval('10_MINUTES')) {
        $cron->log(array('application', 'checking for expired trial accounts'));
    }

});
*/

//include_once __DIR__ . '/forms/create.site.form.php';


$user = \Model\Session::load_user();

if ($user) {
    \Kernel()->events('ui')->addFilter('load_css', 'application_bootstrap', function ($styles) use ($user) {

        if ($user->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) == false && $user->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN) == false && (\Model\User::find($user->user_id)->user_type != SYSTEM_USER_TYPE_STAFF && \Model\User::find($user->user_id)->user_type != SYSTEM_USER_TYPE_ADMIN)) {

            if (!isset($styles['v3/lib/ionicons/css/ionicons.min.css'])) {
                $styles['v3/lib/ionicons/css/ionicons.min.css'] = array();
            }
            if (!isset($styles['v3/lib/ionicons/css/ionicons.min.css'])) {
                $styles['v3/lib/ionicons/css/ionicons.min.css'] = array();
            }
            if (!isset($styles['v3/assets/css/dashforge.css'])) {
                $styles['v3/assets/css/dashforge.css'] = array();
            }
            if (!isset($styles['v3/assets/css/dashforge.dashboard.css'])) {
                $styles['v3/assets/css/dashforge.dashboard.css'] = array();
            }
            if (!isset($styles['v3/assets/css/skin.charcoal.css'])) {
                $styles['v3/assets/css/skin.charcoal.css'] = array();
            }
            if (!isset($styles['v3/assets/css/skin.light.css'])) {
                $styles['v3/assets/css/skin.light.css'] = array();
            }
            if (!isset($styles['v3/lib/@fortawesome/fontawesome-free/css/all.min.css'])) {
                $styles['v3/lib/@fortawesome/fontawesome-free/css/all.min.css'] = array();
            }
            if (!isset($styles['v3/lib/availability-calender/dist/simple-calendar.css'])) {
                $styles['v3/lib/availability-calender/dist/simple-calendar.css'] = array();
            }
            if (!isset($styles['assets/vendor/select2/select2.css'])) {
                $styles['assets/vendor/select2/select2.css'] = array();
            }
        }
        return $styles;
    });

    \Kernel()->events('ui')->addFilter('load_script', 'application_bootstrap', function ($scripts) use ($user) {


        if ($user->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) == false && $user->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN) == false && (\Model\User::find($user->user_id)->user_type != SYSTEM_USER_TYPE_STAFF && \Model\User::find($user->user_id)->user_type != SYSTEM_USER_TYPE_ADMIN)) {


            if (!isset($scripts['v3/lib/jquery/jquery.min.js'])) {
                $scripts['v3/lib/jquery/jquery.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/bootstrap/js/bootstrap.bundle.min.js'])) {
                $scripts['v3/lib/bootstrap/js/bootstrap.bundle.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/jqueryui/jquery-ui.min.js'])) {
                $scripts['v3/lib/jqueryui/jquery-ui.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/feather-icons/feather.min.js'])) {
                $scripts['v3/lib/feather-icons/feather.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/perfect-scrollbar/perfect-scrollbar.min.js'])) {
                $scripts['v3/lib/perfect-scrollbar/perfect-scrollbar.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/jquery.flot/jquery.flot.js'])) {
                $scripts['v3/lib/jquery.flot/jquery.flot.js'] = array();
            }
            if (!isset($scripts['v3/lib/jquery.flot/jquery.flot.stack.js'])) {
                $scripts['v3/lib/jquery.flot/jquery.flot.stack.js'] = array();
            }
            if (!isset($scripts['v3/lib/jquery.flot/jquery.flot.resize.js'])) {
                $scripts['v3/lib/jquery/jquery.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/chart.js/Chart.bundle.min.js'])) {
                $scripts['v3/lib/chart.js/Chart.bundle.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/jqvmap/jquery.vmap.min.js'])) {
                $scripts['v3/lib/jqvmap/jquery.vmap.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/jqvmap/maps/jquery.vmap.usa.js'])) {
                $scripts['v3/lib/jqvmap/maps/jquery.vmap.usa.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/dashforge.js'])) {
                $scripts['v3/assets/js/dashforge.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/dashforge.aside.js'])) {
                $scripts['v3/assets/js/dashforge.aside.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/dashforge.sampledata.js'])) {
                $scripts['v3/assets/js/dashforge.sampledata.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/dashboard-one.js'])) {
                $scripts['v3/assets/js/dashboard-one.js'] = array();
            }
            if (!isset($scripts['v3/lib/js-cookie/js.cookie.js'])) {
                $scripts['v3/lib/js-cookie/js.cookie.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/dashforge.settings.js'])) {
                $scripts['v3/assets/js/dashforge.settings.js'] = array();
            }
            if (!isset($scripts['v3/lib/cleave.js/cleave.min.js'])) {
                $scripts['v3/lib/cleave.js/cleave.min.js'] = array();
            }
            if (!isset($scripts['v3/lib/availability-calender/dist/jquery.simple-calendar.js'])) {
                $scripts['v3/lib/availability-calender/dist/jquery.simple-calendar.js'] = array();
            }
            if (!isset($scripts['assets/vendor/select2/select2.min.js'])) {
                $scripts['assets/vendor/select2/select2.min.js'] = array();
            }
            if (!isset($scripts['v3/assets/js/custom.js'])) {
                $scripts['v3/assets/js/custom.js'] = array();
            }
        }

        return $scripts;
    });
}
