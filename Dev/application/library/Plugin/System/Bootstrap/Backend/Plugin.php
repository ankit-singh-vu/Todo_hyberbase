<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Bootstrap\Backend;
use System, RuntimeException;

define('SYSTEM_USER_TYPE_ADMIN', 501);
define('SYSTEM_USER_TYPE_STAFF', 502);
define('SYSTEM_USER_TYPE_CUSTOMER', 503);
define('SYSTEM_USER_TYPE_LEAD', 504);

define('SYSTEM_TENANT_TYPE_OWNER', 501);
define('SYSTEM_TENANT_TYPE_ADMIN', 502);
define('SYSTEM_TENANT_TYPE_CUSTOMER', 503);
define('SYSTEM_TENANT_TYPE_LEAD', 504);



/**
 * Layout Backend
 *
 * This plugin is basically a template creating the backend of the application
 * we are building.
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
        $kernel->loader()->registerNamespace('Module', __DIR__);
        $kernel->loader()->registerNamespace('Model', __DIR__);

        $kernel->events('system')->bind('routes_loaded', 'plugin_app_backend', function(\System\Router $router) {
            $router->route('default', '/')
                ->defaults(array('module' => 'bindex', 'action' => 'index', 'format' => 'html'));
        });

        $kernel->events('ui')->addFilter('admin_ajax_navigation', 'plugin_app_backend', function($navigation) {
            if(!isset($navigation['left-navigation'])) {
                $navigation['left-navigation'] = array('type' => 'admin-left-navigation');
            }
            if(!isset($navigation['user-navigation-user'])) {
                $navigation['user-navigation-user'] = array('type' => 'admin-user-navigation', 'segment' => 'user');
            }
            if(!isset($navigation['user-navigation-tenant'])) {
                $navigation['user-navigation-tenant'] = array('type' => 'admin-user-navigation', 'segment' => 'tenant');
            }
            if(!isset($navigation['user-navigation-access'])) {
                $navigation['user-navigation-access'] = array('type' => 'admin-user-navigation', 'segment' => 'access');
            }
            return $navigation;
        });
    }

}