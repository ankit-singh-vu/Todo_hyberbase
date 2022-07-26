<?php

/**
 * Created by PhpStorm.
 * User: dyutiman
 * Date: 13/4/18
 * Time: 6:55 AM
 */

$user = \Model\Session::load_user();

if ($user) {
    \Kernel()->events('ui')->addFilter('load_css', 'plugin_bootstrap_bootstrap', function ($styles) use ($user) {
        if ($user->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) || $user->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN) || \Model\User::find($user->user_id)->user_type == SYSTEM_USER_TYPE_STAFF || \Model\User::find($user->user_id)->user_type == SYSTEM_USER_TYPE_ADMIN) {
            if (!isset($styles['assets/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'])) {
                $styles['assets/vendor/bootstrap-toggle/css/bootstrap-toggle.min.css'] = array();
            }
            if (!isset($styles['assets/vendor/jqueryte/jquery-te-1.4.0.css'])) {
                $styles['assets/vendor/jqueryte/jquery-te-1.4.0.css'] = array();
            }
            if (!isset($styles['assets/css/application.css'])) {
                $styles['assets/css/application.css'] = array();
            }
            if (!isset($styles['assets/css/style.css'])) {
                $styles['assets/css/style.css'] = array();
            }
            if (!isset($styles['assets/css/application-ie9-part2.css'])) {
                $styles['assets/css/application-ie9-part2.css'] = array('condition' => 'IE 9');
            }
            if (!isset($styles['//fonts.googleapis.com/css?family=Titillium+Web:600'])) {
                $styles['//fonts.googleapis.com/css?family=Titillium+Web:600'] = array();
            }
            if (!isset($styles['assets/vendor/bootstrap-select/dist/css/bootstrap-select.css'])) {
                $styles['assets/vendor/bootstrap-select/dist/css/bootstrap-select.css'] = array();
            }
        }
        return $styles;
    });

    \Kernel()->events('ui')->addFilter('load_script', 'plugin_bootstrap_bootstrap', function ($scripts) use ($user) {

        if ($user->user->is_tag_present(SYSTEM_USER_TYPE_STAFF) || $user->user->is_tag_present(SYSTEM_USER_TYPE_ADMIN) || \Model\User::find($user->user_id)->user_type == SYSTEM_USER_TYPE_STAFF || \Model\User::find($user->user_id)->user_type == SYSTEM_USER_TYPE_ADMIN) {

            if (!isset($scripts['assets/vendor/jquery/dist/jquery.min.js'])) {
                $scripts['assets/vendor/jquery/dist/jquery.min.js'] = array();
            }
            if (!isset($scripts['assets/vendor/jquery-ui/jquery-ui.min.js'])) {
                $scripts['assets/vendor/jquery-ui/jquery-ui.min.js'] = array();
            }

            if (!isset($scripts['assets/vendor/jquery-pjax/jquery.pjax.js'])) {
                $scripts['assets/vendor/jquery-pjax/jquery.pjax.js'] = array();
            }

            if (!isset($scripts['assets/vendor/popper.js/dist/umd/popper.js'])) {
                $scripts['assets/vendor/popper.js/dist/umd/popper.js'] = array();
            }
            if (!isset($scripts['assets/vendor/bootstrap/dist/js/bootstrap.js'])) {
                $scripts['assets/vendor/bootstrap/dist/js/bootstrap.js'] = array();
            }
            if (!isset($scripts['assets/vendor/bootstrap/js/dist/util.js'])) {
                $scripts['assets/vendor/bootstrap/js/dist/util.js'] = array();
            }
            if (!isset($scripts['assets/vendor/slimScroll/jquery.slimscroll.js'])) {
                $scripts['assets/vendor/slimScroll/jquery.slimscroll.js'] = array();
            }
            if (!isset($scripts['assets/vendor/widgster/widgster.js'])) {
                $scripts['assets/vendor/widgster/widgster.js'] = array();
            }

            if (!isset($scripts['assets/vendor/blockui/jquery.blockUI.js'])) {
                $scripts['assets/vendor/blockui/jquery.blockUI.js'] = array();
            }

            if (!isset($scripts['assets/vendor/bootstrap-notify/bootstrap-notify.js'])) {
                $scripts['assets/vendor/bootstrap-notify/bootstrap-notify.js'] = array();
            }

            if (!isset($scripts['assets/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'])) {
                $scripts['assets/vendor/bootstrap-toggle/js/bootstrap-toggle.min.js'] = array();
            }

            if (!isset($scripts['assets/vendor/pace.js/pace.js'])) {
                $scripts['assets/vendor/pace.js/pace.js'] = array(
                    'data-pace-options' => array(
                        'target' => '.content-wrap',
                        'ghostTime' => 1000
                    )
                );
            }

            if (!isset($scripts['assets/vendor/bootstrap-select/dist/js/bootstrap-select.js'])) {
                $scripts['assets/vendor/bootstrap-select/dist/js/bootstrap-select.js'] = array();
            }
            if (!isset($scripts['assets/vendor/jquery-touchswipe/jquery.touchSwipe.js'])) {
                $scripts['assets/vendor/jquery-touchswipe/jquery.touchSwipe.js'] = array();
            }
            if (!isset($scripts['assets/vendor/sammy/sammy.js'])) {
                $scripts['assets/vendor/sammy/sammy.js'] = array();
            }
            if (!isset($scripts['assets/vendor/jqueryte/jquery-te-1.4.0.min.js'])) {
                $scripts['assets/vendor/jqueryte/jquery-te-1.4.0.min.js'] = array();
            }
            if (!isset($scripts['assets/js/settings.js'])) {
                $scripts['assets/js/settings.js'] = array();
            }
            if (!isset($scripts['assets/js/app.js'])) {
                $scripts['assets/js/hyperbase.js'] = array();
            }
            if (!isset($scripts['assets/js/app.js'])) {
                $scripts['assets/js/app.js'] = array();
            }
            if (!isset($scripts['assets/js/init.js'])) {
                $scripts['assets/js/init.js'] = array();
            }
        }

        return $scripts;
    });
}
