<?php
namespace System\Module;
use System;

/**
 * Base application module controller
 * Used as a base module class other modules must extend from
 *
 * @package system
 * @link http://systemframework.com/
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
abstract class ControllerAbstract
{
    protected $kernel;
    
    public $view;
    
    /**
     * Kernel to handle dependenies
     */
    public function __construct(System\Kernel $kernel)
    {
        $this->kernel = $kernel;
        $this->view = $this->template($this->kernel->request()->param('action'));

        $this->kernel->response()->header('x-powered-by',
            $this->kernel->config('app.name', 'HyperBase') . '/'
            . $this->kernel->config('app.version', HYPERBASE_VERSION)
        );

        //@todo: This is not working. Probably need to configure this from apache configuration.
        $this->kernel->response()->header('Server', 'HyperBase');
    }
    
    
    /**
     * Called immediately upon instantiation, before the action is called
     */
    public function init($action = null) {}
    
    
    /**
     * Return current class path
     */
    public function path()
    {
        $class = get_called_class(); // Thank you late static binding!
        $path = str_replace('\\', '/', str_replace('\\Controller', '', $class));
        foreach($this->kernel->loader()->getNamespacePaths('Module') as $mpath) {
           if(file_exists($mpath . '/' . $path . '/views')) {
               return $mpath . '/' . $path;
           }
        }   
        return \Kernel()->config('app.path.root') . '/' . $path;
    }
    
    
    /**
     * Return current module name, based on class naming conventions
     * Expected: \Module\[Name]\Controller
     */
    public function name()
    {
        $name = str_replace("\\Controller", "", get_class($this));
        return str_replace("Module\\", "", $name);
    }
    
    
    /**
     * New module view template
     *
     * @param string $file Template filename
     * @param string $format Template output format
     * @return \System\View\Template
     */
    public function template($file, $format = null)
    {
        $kernel = $this->kernel;
        $format = (null !== $format) ? $format : $kernel->request()->format;
        $view = new System\View\Template($file, $format, $this->path() . "/views/");
        return $view;
    }
    
    public function disableLayout()
    {
        $GLOBALS['__HB_LAYOUT_DISABLE'] = true;
    }


    /**
     * New generic module response
     *
     * @param string $file Template filename
     * @return \System\Module\Response
     */
    public function response($content, $status = 200)
    {
        $res = new System\Module\Response();
        return $res->content($content)
            ->status($status);
    }

    /**
     * @param $name
     * @return bool
     */
    protected function setPointers($name)
    {
        if(!isset($GLOBALS['__EXT']['POINTERS'])) {
            $GLOBALS['__EXT']['POINTERS'] = array();
        }
        if(is_array($name)) {
            $GLOBALS['__EXT']['POINTERS'] = array_merge($GLOBALS['__EXT']['POINTERS'], $name);
            return true;
        }
        $GLOBALS['__EXT']['POINTERS'][] = $name;
        return true;
    }

    protected function setPageHeading($heading)
    {
        $GLOBALS['__EXT']['PAGE_HEADING'] = $heading;
        return true;
    }

    protected function enableHeaderTabs()
    {
        $GLOBALS['__EXT']['HEADER_TAB']['ENABLED'] = true;
        return true;
    }

    protected function disableHeaderTabs()
    {
        $GLOBALS['__EXT']['HEADER_TAB']['ENABLED'] = false;
        return true;
    }

    protected function hideNavigationChild($type)
    {
        $GLOBALS['__EXT']['NAGIVATION_CHILD'][$type]['HIDE'] = true;
        return true;
    }

}