<?php
namespace System\View\Helper;

/**
 * Base View Helper
 * 
 * @package system
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @link http://systemframework.com/
 */
abstract class HelperAbstract
{
    protected $kernel;
    protected $_view;
    
    public function __construct(\System\Kernel $kernel, \System\View\Template $view)
    {
        $this->kernel = $kernel;
        $this->_view = $view;
        
        $this->init();
    }
    
    
    /**
     * Initialization called upon instantiation
     * Used so extending classes don't have to override constructor
     */
    public function init() {}
}