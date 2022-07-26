<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\DB\Attributes;
use System, RuntimeException;

/**
 * DB/Attribute Plugin
 *
 * This plugin allows to add tags and variables to any record in
 * and of the DB tables, the model of which is extended from "\Model\AttributeAbstract"
 */
class Plugin
{
    /**
     * @var
     */
    protected $kernel;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $kernel->loader()->registerNamespace('Model', __DIR__);
    }

}