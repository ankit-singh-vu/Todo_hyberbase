<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Layout;
use System, RuntimeException;

/**
 * Layout Plugin
 * Wraps layout template around content result from main dispatch loop
 */
class Plugin
{
    protected $kernel;

    protected $http_error_code = array(
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
    );

    protected $http_default_error_code = 500;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        // Add 'wrapLayout' method as callback for 'dispatch_content' filter
        $kernel->events()->addFilter('dispatch_content', 'System_layout_wrap', array($this, 'wrapLayout'));
        $kernel->events()->addFilter('dispatch_exception', 'System_exception_layout', array($this, 'renderError'));

        $kernel->addMethod('disableLayout', function() {
            $GLOBALS['__HB_LAYOUT_DISABLE'] = true;
        });

        $this->kernel = $kernel;
    }

    public function renderError($e)
    {
        if($this->kernel->config('app.mode.development', false) == true) {
            ___debug($e);
        }
        $this->kernel->notify_exception($e);
        ob_start();
        include_once '../application/layouts/app.error.html.php';
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Wrap layout around returned content from primary dispatch
     *
     * @return mixed $content Raw content string, system\Module\Response object, or generic object that implements __toString
     */
    public function wrapLayout($content)
    {
        if(isset($GLOBALS['__HB_LAYOUT_DISABLE']) && $GLOBALS['__HB_LAYOUT_DISABLE'] == true) {
            return $content;
        }
        
        $kernel   = \Kernel();
        $this->kernel = $kernel;
        $request  = $kernel->request();
        $response = $kernel->response();

        $response->contentType('text/html');
        
        $layoutName = null;
        if($content instanceof System\View\Template) {
            $layoutName = $content->layout();
        }
        // Use config template if none other specified and request is not Ajax or CLI
        if(null === $layoutName && !$request->isAjax() && !$request->isCli()) {
            $layoutName = $kernel->config('app.layout', 'app');
        }

        if($layoutName) {
            $layout = new \System\View\Template($layoutName, $request->format);
            $layout->path($kernel->config('app.path.layouts'))
                ->format($request->format);
                        
            // Ensure layout exists
            if (false === $layout->exists()) {
                return $content;
            }

            // Pass along set response status and data if we can
            if($content instanceof System\Module\Response) {
                $layout->status($content->status());
                $layout->errors($content->errors());
            }

            // Pass set title up to layout to override at template level
            if($content instanceof System\View\Template) {
                // Force render layout so we can pull out variables set in template
                $contentRendered = $content->content();
                $layout->head()->title($content->head()->title());
                $content = $contentRendered;
            }    
            
            $layout->set(array(
                'kernel'        => $kernel,
                'content'       => $content             
            ));
            
            return $layout;
        }

        return $content;
    }
}