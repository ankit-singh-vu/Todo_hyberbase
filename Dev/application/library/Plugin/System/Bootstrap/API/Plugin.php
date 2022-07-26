<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Bootstrap\API;
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
        $kernel->loader()->registerNamespace('Module', __DIR__);

        $kernel->events('system')->addFilter('load_controller_class_name', 'plugin_bootstrap', function($module) use($kernel) {
            $api_access = $kernel->request()->header('x-hb-api-access-token');
            if($api_access !== false) {
                return 'Module\\' . $module . '\API';
            }
            $agent_access = $kernel->request()->header('x-hb-rpc-access-token');
            if($agent_access !== false) {
                //___debug('ACC');
                return 'Module\\' . $module . '\Agent';
            }
            return $module;
        });
    }

}