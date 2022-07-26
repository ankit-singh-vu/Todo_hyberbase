<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Form;
use System, RuntimeException;

/**
 * Layout Plugin
 * Wraps layout template around content result from main dispatch loop
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
        $kernel->loader()->registerNamespace('App', __DIR__);
    }

}