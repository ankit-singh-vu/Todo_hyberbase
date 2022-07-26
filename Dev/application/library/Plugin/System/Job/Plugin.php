<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Job;
use System, RuntimeException;

/**
 * Job Plugin
 *
 * Executes, Monitors and Manges LRP (Long Running Process)
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
        $kernel->loader()->registerNamespace('Job', $kernel->config('app.path.root'));
    }

}