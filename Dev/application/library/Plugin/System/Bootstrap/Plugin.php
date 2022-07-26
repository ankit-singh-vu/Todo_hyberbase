<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Bootstrap;
use System, RuntimeException;

/**
 * Bootstrap Plugin
 *
 * This provide a basic UI framework for the application
 * This plugin comes with basic functionalists like login, registration
 * users, plans, subscriptions ... etc.
 */
class Plugin
{
    protected $kernel;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $this->kernel = $kernel;
        $kernel->loader()->registerNamespace('Model', __DIR__);
        $kernel->loader()->registerNamespace('Module', __DIR__);
        $kernel->loader()->registerNamespace('App', __DIR__);

        $kernel->events('system')->bind('boot_start', 'plugin_bootstrap', function() use($kernel) {
            //$this->loadSubscriptionPlugins();
            foreach ($kernel->loader()->getNamespacePaths('Module') as $path) {
                foreach (scandir($path . '/Module') as $mod) {
                    if (!in_array($mod, array('.', '..'))) {
                        if (file_exists($path . '/Module/' . $mod . '/manifest.php')) {
                            include_once $path . '/Module/' . $mod . '/manifest.php';
                        }
                    }
                }
                if (file_exists($path . '/bootstrap.php')) {
                    include_once $path . '/bootstrap.php';
                }
            }
        });

        $kernel->events('ui')->addFilter('ajax_navigation', 'plugin_bootstrap', function($navigation) {
            if(!isset($navigation['left-navigation'])) {
                $navigation['left-navigation'] = array('type' => 'left-navigation');
            }
            if(!isset($navigation['user-navigation-user'])) {
                $navigation['user-navigation-user'] = array('type' => 'user-navigation', 'segment' => 'user');
            }
            if(!isset($navigation['user-navigation-tenant'])) {
                $navigation['user-navigation-tenant'] = array('type' => 'user-navigation', 'segment' => 'tenant');
            }
            if(!isset($navigation['user-navigation-access'])) {
                $navigation['user-navigation-access'] = array('type' => 'user-navigation', 'segment' => 'access');
            }
            return $navigation;
        });

        $kernel->events('system')->bind('routes_loaded', 'plugin_bootstrap', function($route) use($kernel) {

            $enforcement = $kernel->events('system')->filter('enforce_user_permission', array());
            return true;

        });

    }

    public function loadSubscriptionPlugins()
    {
        $session = \Model\Session::_load();
        if($session) {
            $tenant = $session->user->get_current_web_tenant();
            $this->kernel->loader()->registerNamespace('Plugin', '/mnt/custom/' . $tenant->uuid);
        }

        //Load all subscription based plugins over here
    }


}