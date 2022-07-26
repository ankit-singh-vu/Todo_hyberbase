<?php
/**
 * The Kernel file is the central core of the framework. The framework is looaded
 * and runned the Kernal Object, and on it runs the application. The Kernal class
 * can be loaded only once. We can load or get the instance of  \System\Kernel by
 * calling the \Kernel() function.
 *
 * @package    System
 * @subpackage Kernel
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

namespace System;

/**
 * System / Application Kernel.
 *
 * Functions:
 *   - Service Locator/Registry for object storage and central access
 *   - Configuration storage and retrieval application-wide
 *   - Extendable to create new functionality at runtime
 */
class Kernel
{
    /**
     * Contains the \System\Kernel Object, to prevent multiple instance of the
     * mentioned calss.
     *checkPHPSyntex
     * @var \System\Kernel
     */
    protected static $self;

    /**
     * Configuration settings registered with the instance.
     *
     * @var array
     */
    protected static $cfg = array();
    
    /**
     * Trace data.
     *
     * @var array
     */
    protected static $trace = array();
    
    /**
     * Trace Memory.
     *
     * @var integer
     */
    protected static $traceMemoryStart = 0;
    
    /**
     * Trace Memory Limit.
     *
     * @var integer
     */
    protected static $traceMemoryLast = 0;
    
    /**
     * Trace Time Start.
     *
     * @var integer
     */
    protected static $traceTimeStart = 0;
    
    /**
     * Trace Time Last Logged.
     *
     * @var integer
     */
    protected static $traceTimeLast = 0;
    
    /**
     * Current stae of debugging.
     *
     * @var boolean
     */
    protected static $debug = false;

    /**
     * Last Dispatch.
     *
     * @var array
     */
    protected $lastDispatch = array();
    
    /**
     * Array of loaded instances.
     *
     * @var array
     */
    protected $instances = array();
    
    /**
     * An array of callbacks registered with the class instance.
     *
     * @var array
     */
    protected $callbacks = array();

    /**
     * A list of loaded plugins.
     *
     * @var array
     */
    protected $loadedPlugin = array();


    protected $pluginObject = array();
    
    /**
     * An array of registered permission callbacks.
     *
     * @var array
     */
    protected $app_permission = array();
    
    /**
     * An array of cached user permissions.
     *
     * @var array
     */
    protected $user_permission = array();

    protected $user_permission_list = null;

    /**
     * Returns an instance of class itself. In this case \System\Kernel.
     *
     * @param array $config Array of configuration settings to load.
     *
     * @return \System\Kernel
     */
    public static function getInstance(array $config = array())
    {
        if (!is_object(static::$self)) {
            $className    = get_called_class();
            static::$self = new $className($config);
        } else {
            // Add new config settings if given.
            if (is_array($config) && count($config) > 0) {
                static::$self->config($config);
            }
        }
        return static::$self;
    }//end getInstance()
    
    /**
     * Return the singular for of a prulal word.  Here is the list of rules.
     * To add a scenario, Add the plural ending as the key and the singular ending
     * as the value for that key. This could be turned into a preg_replace and
     * probably will be eventually, but for now, this is what it is.
     *
     * Note: The first rule has a value of false since we don't want to mess
     * with words that end with double 's'. We normally wouldn't have to create
     * rules for words we don't want to mess with, but the last rule (s) would
     * catch double (ss) words if we didn't stop before it got to that rule.
     *
     * @param string $word The input plural word.
     *
     * @return string
     */
    public function depluralize($word)
    {
        $rules = array(
            'ss' => false,
            'os' => 'o',
            'ies' => 'y',
            'xes' => 'x',
            'oes' => 'o',
            'ies' => 'y',
            'ves' => 'f',
            's' => '');
        foreach (array_keys($rules) as $key) {
            if (substr($word, (strlen($key) * -1)) != $key) {
                continue;
            }
            if ($key === false) {
                return $word;
            }
            return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key];
        }
        return $word;
    }//end depluralize()

    /**
     * Protected constructor to enforce singleton pattern.
     *
     * @param array $config Array of configuration settings to store. Passed array will be stored as a static variable and automatically merged with previous stored settings.
     */
    protected function __construct(array $config = array())
    {
        $this->config($config);

        // Save memory starting point.
        static::$traceMemoryStart = memory_get_usage();
        static::$traceTimeStart   = microtime(true);
        // Set last as current starting for good zero-base.
        static::$traceMemoryLast = static::$traceMemoryStart;
        static::$traceTimeLast   = static::$traceTimeStart;
    }//end __construct()

    /**
     * Return configuration value.
     *
     * @param mixed  $value   If string: Value key to search for, If array: Merge given array over current config settings.
     * @param string $default Default value to return if $value not found.
     *
     * @return string
     */
    public function config($value = null, $default = false)
    {
        if (is_array($value)) {
            // Setter.
            if (count($value) > 0) {
                // Merge given config settings over any previous ones (if $value is array).
                static::$cfg = $this->arrayMergeRecursiveReplace(static::$cfg, $value);
            }
        } else {
            // Getter.
            // Config array is static to persist across multiple instances.
            $cfg = static::$cfg;

            // No value passed - return entire config array.
            if ($value === null) {
                return $cfg;
            }

            // Find value to return.
            if (strpos($value, '.') !== false) {
                $cfgValue   = $cfg;
                $valueParts = explode('.', $value);
                foreach ($valueParts as $valuePart) {
                    if (isset($cfgValue[$valuePart])) {
                        $cfgValue = $cfgValue[$valuePart];
                    } else {
                        $cfgValue = $default;
                    }
                }
            } else {
                $cfgValue = $cfg;
                if (isset($cfgValue[$value])) {
                    $cfgValue = $cfgValue[$value];
                } else {
                    $cfgValue = $default;
                }
            }
            return $cfgValue;
        }//end if
    }//end config()

    /**
     * Factory method for loading and instantiating new objects.
     *
     * @param string $className Name of the class to attempt to load.
     * @param array  $params    Array of parameters to pass to instantiate new object with.
     *
     * @return object Instance of the class requested.
     *
     * @throws InvalidArgumentException Throws xception in case of invalid input.
     */
    public function factory($className, array $params = array())
    {
        $instanceHash = md5($className.var_export($params, true));

        // Return already instantiated object instance if set.
        if (isset($this->instances[$instanceHash])) {
            return $this->instances[$instanceHash];
        }

        // Return new class instance.
        // Reflection is known for incurring overhead - hack to avoid it if we can.
        $paramCount = count($params);
        if (0 === $paramCount) {
            $instance = new $className();
        } elseif (1 === $paramCount) {
            $instance = new $className(current($params));
        } elseif (2 === $paramCount) {
            $instance = new $className($params[0], $params[1]);
        } elseif (3 === $paramCount) {
            $instance = new $className($params[0], $params[1], $params[2]);
        } else {
            $class    = new \ReflectionClass($className);
            $instance = $class->newInstanceArgs($args);
        }
        return $this->setInstance($instanceHash, $instance);
    }//end factory()

    /**
     * Class-level object instance cache
     * Note: This function does not check if $class is currently in use or already instantiated.
     *  This will override any previous instances of $class within the $instances array.
     *
     * @param string $hash     String Hash or name of the object instance to cache.
     * @param object $instance Object Instance of object you wish to cache.
     *
     * @return object
     */
    public function setInstance($hash, $instance)
    {
        $this->instances[$hash] = $instance;

        return $instance;
    }//end setInstance()

    /**
     * Custom error-handling function for failed file include error surpression.
     *
     * @param string  $errno   Error Number.
     * @param string  $errstr  Error Text/Description.
     * @param string  $errfile Error File.
     * @param integer $errline Error Line Number.
     *
     * @return boolean
     *
     * @throws Exception Throws an exception.
     */
    protected function throwError($errno, $errstr, $errfile, $errline)
    {
        $msg = "";
        switch ($errno) {
            case E_USER_ERROR:
            case E_USER_WARNING:
                $msg .= "<b>ERROR</b> [$errno] $errstr<br />\n";
                $msg .= "  Fatal error on line $errline in file $errfile";
                $msg .= ", PHP ".PHP_VERSION." (".PHP_OS.")<br />\n";
                $msg .= "Aborting...<br />\n";
                throw new Exception($msg);
            break;
            case E_USER_NOTICE:
            default:
        }
        // Don't execute PHP internal error handler.
        return true;
    }//end throwError()

    /**
     * Get router object.
     *
     * @return \System\Router
     */
    public function router()
    {
        return $this->factory(__NAMESPACE__.'\Router');
    }//end router()

    /**
     * Get request object.
     *
     * @return \System\Request
     */
    public function request()
    {
        return $this->factory(__NAMESPACE__.'\Request');
    }//end request()

    /**
     * Get HTTP REST client.
     *
     * @return object
     */
    public function client()
    {
        return $this->factory(__NAMESPACE__.'\Client');
    }//end client()

    /**
     * Send HTTP response header.
     *
     * @param integer $statusCode HTTP status code to set.
     *
     * @return \Sysytem\Responce
     */
    public function response($statusCode = null)
    {
        $response = $this->factory(__NAMESPACE__.'\Response');
        if (is_numeric($statusCode)) {
            $response->status($statusCode);
        }
        return $response;
    }//end response()

    /**
     * Get class loader object.
     *
     * @return object
     */
    public function loader()
    {
        $class = __NAMESPACE__.'\ClassLoader';
        if (!class_exists($class, false)) {
            include __DIR__.'/ClassLoader.php';
        }

        return $this->factory($class);
    }//end loader()

    /**
     * Return a resource object to work with.
     *
     * @param array $data Data for loading the resource.
     *
     * @return \System\Resource
     */
    public function resource(array $data = array())
    {
        return new Resource($data);
    }//end resource()

    /**
     * Return a session object to work with.
     *
     * @return object Returns the session object.
     */
    public function session()
    {
        return $this->factory(__NAMESPACE__.'\Session');
    }//end session()

    /**
     * Get events object with given namespace.
     *
     * @param string $ns Event namespace (default is 'system').
     *
     * @return object
     */
    public function events($ns = 'system')
    {
        return $this->factory(__NAMESPACE__.'\Events', array($ns));
    }//end events()

    public function apiListen($uri, $callback) {
        return $this->events('api')->addFilter($uri, 'apicall', $callback);
    }
    /**
     * Send HTTP 302 redirect.
     *
     * @param string $url URL to redirect to.
     *
     * @return null
     */
    public function redirect($url='/')
    {
        header("Location: ".$url);
        exit();
        return null;
    }//end redirect()

    /**
     * Trace messages and return message stack
     * Used for debugging and performance monitoring.
     *
     * @param string  $msg      Log Message (optional).
     * @param array   $data     Arroy of any data to log that is related to the message (optional).
     * @param string  $function Function or Class::Method call (optional).
     * @param string  $file     File path where message originated from (optional).
     * @param integer $line     Line of the file where message originated (optional).
     * @param boolean $internal Set true to mast as internal (optional).
     *
     * @return array Message stack
     */
    public function trace($msg = null, array $data = array(), $function = null, $file = null, $line = null, $internal = false)
    {
        // Don't incur the overhead if not in debug mode.
        if (!static::$debug) {
            return false;
        }

        // Build log entry.
        if (null !== $msg) {
            $entry = array(
                'message'  => $msg,
                'data'     => $data,
                'function' => $function,
                'file'     => $file,
                'line'     => $line,
                'internal' => (int) $internal,
                );
            // Only log time & memory for non-internal method calls.
            if (!$internal) {
                $currentTime   = microtime(true);
                $currentMemory = memory_get_usage();
                $entry        += array(
                    'time'         => ($currentTime - static::$traceTimeLast),
                    'time_total'   => $currentTime - static::$traceTimeStart,
                    'memory'       => $currentMemory - static::$traceMemoryLast,
                    'memory_total' => $currentMemory - static::$traceMemoryStart,
                    );
                // Store as last run.
                static::$traceTimeLast   = $currentTime;
                static::$traceMemoryLast = $currentMemory;
            }
            static::$trace[] = $entry;
        }//end if

        return static::$trace;
    }//end trace()

    /**
     * Internal message/action trace - to separate the internal function calls
     * from the stack.
     *
     * @param string|null $msg      Message to be logged (optional).
     * @param array       $data     Additional data to be logged (optional).
     * @param string      $function Name of the function (optional).
     * @param string      $file     Name of the file (optional).
     * @param integer     $line     Line number (optional).
     *
     * @return boolean
     */
    protected function traceInternal($msg = null, array $data = array(), $function = null, $file = null, $line = null)
    {
        // Don't incur the overhead if not in debug mode.
        if (!static::$debug) {
            return false;
        }
        // Log with last parameter as 'true' - last param will always be internal marker.
        return $this->trace($msg, $data, $function, $file, $line, true);
    }//end traceInternal()

    /**
     * Load and return instantiated module class.
     *
     * @param string     $module         Name of the Module.
     * @param boolean    $init           Set ture to run the init() method.
     * @param array|null $dispatchAction Dispatch Action.
     *
     * @return \System\sModuleClass|boolean
     */
    public function module($module, $init = true, $dispatchAction = null)
    {
        // Clean module name to prevent possible security vulnerabilities.
        $sModule = preg_replace('/[^a-zA-Z0-9_]/', '', $module);

        // Upper-case beginning of each word.
        $sModule      = str_replace(' ', '\\', ucwords(str_replace('_', ' ', $sModule)));



        $sModuleClass = $this->events('system')->filter('load_controller_class_name', $sModule);
        if($sModule == $sModuleClass) {
            $sModuleClass = 'Module\\' . $sModule . '\Controller';
        }

        //___debug(getallheaders());

        // Ensure class exists / can be loaded.
        if (!class_exists($sModuleClass)) {
            return false;
        }

        // Instantiate module class.
        $sModuleObject = new $sModuleClass($this);

        // Run init() setup only if supported.
        if (true === $init) {
            if (method_exists($sModuleObject, 'init')) {
                $sModuleObject->init($dispatchAction);
            }
        }

        return $sModuleObject;
    }//end module()

    /**
     * Load and return instantiated plugin class.
     *
     * @param string  $plugin Name of the plugin to get the instance for.
     * @param boolean $init   Set to true to check and throw exception if calss not found.
     *
     * @return object
     *
     * @throws \InvalidArgumentException When plugin is not found by name.
     */
    public function plugin($plugin, $init = true)
    {
        // Module plugin.
        if (false !== strpos($plugin, 'Module\\')) {
            $sPluginClass = $plugin.'\Plugin';
        } else {
            // Named plugin.
            // Upper-case beginning of each word.
            $sPlugin      = str_replace(' ', '\\', ucwords(str_replace('_', ' ', $plugin)));
            $sPluginClass = 'Plugin\\'.$sPlugin.'\Plugin';
        }
        // Ensure class exists / can be loaded.
        if (!class_exists($sPluginClass, (boolean) $init)) {
            if ($init) {
                throw new Exception\InstanceNotFound("Unable to load plugin ".$sPluginClass.". Remove from app config or ensure plugin files exist in 'app' or 'system' load paths.");
            }
            return false;
        }
        // Instantiate module class.
        $this->loadedPlugin[$plugin] = new $sPluginClass($this);
        return $this->loadedPlugin[$plugin];
    }//end plugin()

    /**
     * Returns if a plugin is loaded or not.
     *
     * @param string $plugin Name of the plugin to be checked.
     *
     * @return boolean
     */
    public function isPluginLoaded($plugin)
    {
        if (isset($this->loadedPlugin[$plugin])) {
            return true;
        }
        return false;
    }//end isPluginLoaded()

    public function load_plugin($plugin)
    {
        if(isset($this->loadedPlugin[$plugin])) {
            return $this->loadedPlugin[$plugin];
        }
        throw new \Exception('Plugin not avilable');
    }

    /**
     * Dispatch module action.
     *
     * @param string $module Name of module to be called.
     * @param string $action Function name to call on module (optional).
     * @param array  $params Parameters to pass to module function (optional).
     *
     * @return mixed String or object that has __toString method
     *
     * @throws Exception\FileNotFound Throws exception if ffile class or method not found.
     */
    public function dispatch($module, $action = 'index', array $params = array())
    {
        if ($module instanceof \System\Module\ControllerAbstract) {
            // Use current module instance.
            $sModuleObject = $module;
        } else {
            // Get module instance.
            $sModuleObject = $this->module($module, true, $action);

            // Does module exist?
            if (false === $sModuleObject) {
                throw new \System\Exception\FileNotFound("Module '" . $module . "' not found");
            }
        }

        // Store last dispatch request info.
        $this->lastDispatch = array(
            'module' => $module,
            'action' => $action,
            'params' => $params,
        );

        //___debug($params[0]->param('format'));

        // Module action callable (includes __call magic function if method missing)?
        if (!is_callable(array($sModuleObject, $action))) {
            throw new Exception\FileNotFound("Module '".$module."' does not have a callable method '".$action."'");
        }

        // Handle result.
        $result = call_user_func_array(array($sModuleObject, $action), $params);

        if (is_array($result)) {
            header('Content-Type: application/json');
            if (isset($result[0])) {
                if ($result[0] instanceof \ActiveRecord\Model) {
                    $responce = array();
                    foreach ($result as $index => $model) {
                        $responce[$index] = $model->toArray();
                    }
                    echo $this->toJson($responce);
                    exit;
                }
            }
            echo $this->toJson($result);
            exit;
        }

        if ($result instanceof \ActiveRecord\Model) {
            header('Content-Type: application/json');
            echo $result->toJson();
            exit;
        }

        return $result;
    }//end dispatch()

    /**
     * Dispatch module action from HTTP request
     * Automatically limits call scope by appending 'Action' or 'Method' to module actions.
     *
     * @param string $module Name of module to be called.
     * @param string $action Function name to call on module (optional).
     * @param array  $params Parameters to merge onto Request object (optional).
     *
     * @return mixed String or object that has __toString method
     */
    public function dispatchRequest($module, $action = 'indexAction', array $params = array())
    {
        $request       = $this->request();
        $requestMethod = $request->method();

        // Append 'Action' or 'Method'.
        if (strtolower($requestMethod) == strtolower($action)) {
            $action = $action.(false === strpos($action, 'Method') ? 'Method' : ''); // Append with 'Method' to limit scope to REST access only.
        } else {
            $action = $action.(false === strpos($action, 'Action') ? 'Action' : ''); // Append with 'Action' to limit scope of available functions from HTTP request.
        }

        // Set params on Request object.
        $request->setParams($params);

        // Run normal dispatch.
        return $this->dispatch($module, $action, array($request));
    }//end dispatchRequest()

    /**
     * Get information about the last dispatch request executed.
     *
     * @return array Dispatch information
     */
    public function lastDispatch()
    {
        return $this->lastDispatch;
    }//end lastDispatch()
    
    /**
     * Generate URL from given params.
     *
     * @param array|string $params      Named URL parts.
     * @param array|null   $routeName   Named querystring URL parts (optional).
     * @param array        $queryParams Query parameters.
     * @param boolean      $qsAppend    Additional append to the url returned.
     *
     * @return type
     *
     * @throws Exception Throws exception incase of invalid input arguments.
     */
    public function url($params = array(), $routeName = null, array $queryParams = array(), $qsAppend = false)
    {
        $urlBase = $this->config('url.root', '');

        // HTTPS Secure URL?
        $isSecure = false;
        if (isset($params['secure']) && true === $params['secure']) {
            $isSecure = true;
            $urlBase  = str_replace('http:', 'https:', $urlBase);
            unset($params['secure']);
        }

        // Detemine what URL is from param types.
        if (is_string($params)) {
            $routeName = $params;
            $params    = array();
        } elseif (!is_array($params)) {
            throw new Exception("First parameter of URL must be array or string route name");
        }

        // Is there query string data?
        $queryString = "";
        $request     = $this->request();
        if (true === $qsAppend && $request->query()) {
            $queryParams = array_merge($request->query(), $queryParams);
        }
        if (count($queryParams) > 0) {
            // Build query string from array $qsData.
            $queryString = http_build_query($queryParams, '', '&amp;');
        } else {
            $queryString = false;
        }

        // Get URL from router object by reverse match.
        $url = str_replace('%2f', '/', strtolower($this->router()->url($params, $routeName)));

        // Use query string if URL rewriting is not enabled.
        if ($this->config('url.rewrite')) {
            $url = $urlBase.$url.(($queryString !== false) ? '?'.$queryString : '');
        } else {
            $url = $urlBase.'?u='.$url.(($queryString !== false) ? '&amp;'.$queryString : '');
        }

        // Return fully assembled URL.
        $url = str_replace('///', '/', $url);

        return $url;
    }//end url()

    /**
     * Truncates a string to a certian length & adds a "..." to the end.
     * This method will never bread a word in the middle.
     *
     * @param string  $string    The string to be processed.
     * @param integer $endlength The length of the returned string.
     * @param string  $end       To be appended after the trimmed string.
     *
     * @return string
     */
    public function truncate($string, $endlength = "30", $end = "...")
    {
        $strlen = strlen($string);
        if ($strlen > $endlength) {
            $trim    = $endlength-$strlen;
            $string  = substr($string, 0, $trim);
            $string .= $end;
        }

        return $string;
    }//end truncate()

    /**
     * Converts underscores to spaces and capitalizes first letter of each word.
     *
     * @param string $word Words to be processed.
     *
     * @return string
     */
    public function formatUnderscoreWord($word)
    {
        return ucwords(str_replace('_', ' ', $word));
    }//end formatUnderscoreWord()

    /**
     * Format given string to valid URL string.
     *
     * @param string $string The string to be processed for a valid url.
     *
     * @return string URL-safe string
     */
    public function formatUrl($string)
    {
        // Allow only alphanumerics, underscores and dashes.
        $string = preg_replace('/([^a-zA-Z0-9_\-]+)/', '-', strtolower($string));
        // Replace extra spaces and dashes with single dash.
        $string = preg_replace('/\s+/', '-', $string);
        $string = preg_replace('|-+|', '-', $string);
        // Trim extra dashes from beginning and end.
        $string = trim($string, '-');

        return $string;
    }//end formatUrl()

    /**
     * Filesize Calculating function
     * Retuns the size of a file in a "human" format.
     *
     * @param integer $size Filesize in bytes.
     *
     * @return string Calculated filesize with units (ex. "4.58 MB")
     */
    public function formatFilesize($size)
    {
        $kb = 1024;
        $mb = 1048576;
        $gb = 1073741824;
        $tb = 1099511627776;

        if ($size < $kb) {
            return $size." B";
        } elseif ($size < $mb) {
            return round($size/$kb, 2)." KB";
        } elseif ($size < $gb) {
            return round($size/$mb, 2)." MB";
        } elseif ($size < $tb) {
            return round($size/$gb, 2)." GB";
        } else {
            return round($size/$tb, 2)." TB";
        }
    }//end formatFilesize()
        
    /**
     * Convets a sandard url into clickable HTML link.
     *
     * <code>
     *      $kernel = \Kernel();
     *      echo $kernel->makeClickableLinks('http://www.mclogics.com/some/link.htm');
     * </code>
     *
     * @param string $s Input url which will be converted to link.
     *
     * @return string
     */
    public function makeClickableLinks($s)
    {
        return preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $s);
    }//end makeClickableLinks()
    
    /**
     * Deletes a directory and all its sub-directory and files.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->rrmdir('path/to/directory');
     * </code>
     *
     * @param string $dir Path of the directory to be deleted.
     *
     * @return boolean
     */
    public function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") {
                        $this->rrmdir($dir."/".$object);
                    } else {
                        unlink($dir."/".$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
            return true;
        }
        return false;
    }//end rrmdir()
    
    /**
     * Searches a given directory and all its sub-directories rescrusively for the
     * given file. retuens an array of the list of paths found containing the
     * named file.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $found_paths = $kernel->searchDirectory('/path/to/directory'. 'filename.php');
     * </code>
     *
     * @param string $dir  Directory path under which the file should be searched.
     * @param string $file Name fo the file to be search (with extension).
     *
     * @return array
     */
    public function searchDirectory($dir, $file)
    {
        $result = array();
        $cdir   = scandir($dir);
        foreach ($cdir as $key => $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . '/' . $value)) {
                    $result = array_merge($result, $this->searchDirectory($dir . '/' . $value, $file));
                } elseif (file_exists($dir . '/' . $file)) {
                    if (!in_array($dir, $result)) {
                        $result[] = $dir;
                    }
                }
            }
        }
        return $result;
    }//end searchDirectory()
    
    /**
     * Generates a random password, which is tough to guess. $available_sets contains
     * the type of charecters that can be used to generate the password. Following
     * are the meaning of each letter used in  $available_sets.
     *
     * l - lowercase letters
     * u - uppercase letters
     * d - digits or numbers
     * s - special charecters
     *
     * <code>
     *      $kernel = \Kernel();
     *      $new_password = $kernel->generatePassword();
     * </code>
     *
     * @param integer $length         The desired length of the password.
     * @param string  $available_sets Contents to be used in the passowrds.
     * @param boolean $add_dashes     Set true to add dashes to the password.
     *
     * @return string
     */
    public function generatePassword($length = 9, $available_sets = 'luds', $add_dashes = false)
    {
        $sets = array();
        if (strpos($available_sets, 'l') !== false) {
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        }
        if (strpos($available_sets, 'u') !== false) {
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        }
        if (strpos($available_sets, 'd') !== false) {
            $sets[] = '23456789';
        }
        if (strpos($available_sets, 's') !== false) {
            $sets[] = '!@#%&*?';
        }
        $all = $password = '';
        foreach ($sets as $set) {
            $password .= $set[array_rand(str_split($set))];
            $all      .= $set;
        }

        $all = str_split($all);
        for ($i = 0; $i < $length - count($sets); $i++) {
            $password .= $all[array_rand($all)];
        }

        $password = str_shuffle($password);

        if (!$add_dashes) {
            return $password;
        }

        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while (strlen($password) > $dash_len) {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password  = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }//end generatePassword()


    /**
     * '(sync_frequency = 20 AND last_sync < ' . ($time - 20) . ')'
    .  ' OR (sync_frequency = '.(60*5).' AND last_sync < ' . ($time - (60*5)) . ')'
    .  ' OR (sync_frequency = '.(60*15).' AND last_sync < ' . ($time - (60*15)) . ')'
    .  ' OR (sync_frequency = '.(60*30).' AND last_sync < ' . ($time - (60*30)) . ')'
    .  ' OR (sync_frequency = '.(60*60).' AND last_sync < ' . ($time - (60*60)) . ')'
    .  ' OR (sync_frequency = '.(60*60*3).' AND last_sync < ' . ($time - (60*60*3)) . ')'
    .  ' OR (sync_frequency = '.(60*60*6).' AND last_sync < ' . ($time - (60*60*6)) . ')'
    .  ' OR (sync_frequency = '.(60*60*12).' AND last_sync < ' . ($time - (60*60*12)) . ')'
    .  ' OR (sync_frequency = '.(60*60*24).' AND last_sync < ' . ($time - (60*60*24)) . ')'
    .  ' OR (sync_frequency = '.(60*60*24*7).' AND last_sync < ' . ($time - (60*60*24*7)) . ')',
     */

    public function syncText($inputSeconds)
    {
        if($inputSeconds == 20) {
            return 'Every 20 sec';
        }
        if($inputSeconds == 60*5) {
            return 'Every 5 minutes';
        }
        if($inputSeconds == 60*15) {
            return 'Every 15 minutes';
        }
        if($inputSeconds == 60*30) {
            return 'Every 30 minutes';
        }
        if($inputSeconds == 60*60) {
            return 'Every hour';
        }
        if($inputSeconds == 60*60*3) {
            return 'Every 3 hours';
        }
        if($inputSeconds == 60*60*6) {
            return 'Every 6 hours';
        }
        if($inputSeconds == 60*60*12) {
            return 'Every 12 hours';
        }
        if($inputSeconds == 60*60*24) {
            return 'Every 24 hours';
        }
        if($inputSeconds == 60*60*24*7) {
            return 'Once Per Week';
        }
    }
    
    /**
     * Converts senonds to applicable number of days, hours, minutes and secounds.
     * Optionally can return as array or as string.
     *
     * <code>
     *      $kernel = \Kernel();
     *      echo $kernel->secondsToTime(8465200); //97 days 23 hrs 31 mins 11 secs.
     * </code>
     *
     * @param string  $inputSeconds Seconds to be converted.
     * @param boolean $show_seconds Set true to show seconds, or false to hide the same.
     * @param boolean $return_array Set true to return as array or false to return as text.
     *
     * @return string|array
     */
    public function secondsToTime($inputSeconds, $show_seconds = false, $return_array = false)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour  = 60 * $secondsInAMinute;
        $secondsInADay    = 24 * $secondsInAnHour;

        // Extract days.
        $days = floor($inputSeconds / $secondsInADay);

        // Extract hours.
        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours       = floor($hourSeconds / $secondsInAnHour);

        // Extract minutes.
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes       = floor($minuteSeconds / $secondsInAMinute);

        // Extract the remaining seconds.
        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds          = ceil($remainingSeconds);

        if ($return_array == true) {
            $obj = array(
                'd' => (int) $days,
                'h' => (int) $hours,
                'm' => (int) $minutes,
                's' => (int) $seconds,
            );
            return $obj;
        }
        $has_other_unt_then_sec = false;

        $dag      = ' day ';
        $dagen    = ' days ';
        $uur      = ' hr ';
        $uren     = ' hrs ';
        $minuut   = ' min ';
        $minuten  = ' mins';
        $seconden = ' secs ';
        $and      = ' and ';

        $responce = '';
        if ($days > 0) {
            if ($days == 1) {
                $responce .= $days . $dag;
            } else {
                $responce .= $days . $dagen;
            }
            $has_other_unt_then_sec = true;
            return $responce;
        }
        if ($hours > 0) {
            if ($hours == 1) {
                $responce .= $hours . $uur;
            } else {
                $responce .= $hours . $uren;
            }
            $has_other_unt_then_sec = true;
        }
        if ($minutes > 0) {
            if ($minutes == 1) {
                $responce .= $minutes . $minuut;
            } else {
                $responce .= $minutes . $minuten;
            }
            $has_other_unt_then_sec = true;
        }
        if ($show_seconds == true) {
            if ($has_other_unt_then_sec != false) {
                $responce .= $and;
            }
            $responce .= $seconds . $seconden;
        } else {
            if ($has_other_unt_then_sec == false) {
                $responce .= ' ' . $seconds . $seconden;
            }
        }
        return $responce;
    }//end secondsToTime()

    /**
     * Generate random string.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $random_string = $kernel->randomString(40);
     * </code>
     *
     * @param integer $length Character length of returned random string.
     *
     * @return string Random string generated.
     */
    public function randomString($length = 32, $use_special_char=true)
    {
        $string   = "";
        if($use_special_char == true) {
            $possible = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789`~!@#$%^&*()-_+=";
        } else {
            $possible = "abcdefghijklmnopqrstuvwxyz0123456789";
        }
        for ($i = 0; $i < $length; $i++) {
            $char    = $possible[mt_rand(0, strlen($possible)-1)];
            $string .= $char;
        }

        return $string;
    }//end randomString()

    /**
     * Convert to useful array style from HTML form input style
     * Useful for matching up input arrays without having to increment a number in field names.
     *
     * Input an array like this:
     * [name]	=>	[0] => "Google"
     *				[1] => "Yahoo!"
     * [url]	=>	[0] => "http://www.google.com"
     *				[1] => "http://www.yahoo.com"
     *
     * And you will get this:
     * [0]	=>	[name] => "Google"
     *			[title] => "http://www.google.com"
     * [1]	=>	[name] => "Yahoo!"
     *			[title] => "http://www.yahoo.com"
     *
     * @param array $input Array to be processed.
     *
     * @return array
     */
    public function arrayFlipConvert(array $input)
    {
        $output = array();
        foreach ($input as $key => $val) {
            foreach ($val as $key2 => $val2) {
                $output[$key2][$key] = $val2;
            }
        }

        return $output;
    }//end arrayFlipConvert()

    /**
     * Merges any number of arrays of any dimensions, the later overwriting
     * previous keys, unless the key is numeric, in whitch case, duplicated
     * values will not be added.
     *
     * The arrays to be merged are passed as arguments to the function.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $merged_array = $kernel->arrayMergeRecursiveReplace($arrayOne, $arrayTwo, $arrayThree);
     * </code>
     *
     * @return array Resulting array, once all have been merged
     */
    public function arrayMergeRecursiveReplace()
    {
        // Holds all the arrays passed.
        $params =  func_get_args();

        // First array is used as the base, everything else overwrites on it.
        $return = array_shift($params);

        // Merge all arrays on the first array.
        foreach ($params as $array) {
            foreach ($array as $key => $value) {
                // Numeric keyed values are added (unless already there).
                if (is_numeric($key) && (!in_array($value, $return))) {
                    if (is_array($value)) {
                        $return[] = $this->arrayMergeRecursiveReplace($return[$key], $value);
                    } else {
                        $return[] = $value;
                    }
                } else {
                    // String keyed values are replaced.
                    if (isset($return[$key]) && is_array($value) && is_array($return[$key])) {
                        $return[$key] = $this->arrayMergeRecursiveReplace($return[$key], $value);
                    } else {
                        $return[$key] = $value;
                    }
                }
            }
        }
        return $return;
    }//end arrayMergeRecursiveReplace()

    /**
     * Custom error handler.
     *
     * @param string $errno   Error Number.
     * @param string $errstr  Error Message.
     * @param string $errfile Error File.
     * @param string $errline Error Line Number.
     *
     * @return boolean
     *
     * @throws Exception Throws an exception for any error recived.
     */
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        $errorMsg = $errstr." (Line: ".$errfile." {".$errline."})";
        if ($errno != E_WARNING && $errno != E_NOTICE && $errno != E_STRICT) {
            throw new Exception($errorMsg, $errno);
        } else {
            return false; // Let PHP handle it.
        }
    }//end errorHandler()

    /**
     * Debug mode switch. Returns the trace log as an array.
     *
     * @param boolean $switch Set true to enaable debug or false to disable.
     *
     * @return array
     */
    public function debug($switch = true)
    {
        static::$debug = (boolean) $switch;
        return $this->trace();
    }//end debug()

    /**
     * Print out an array or object contents in preformatted text
     * Useful for debugging and quickly determining contents of variables.
     *
     * <code>
     *      $kernel = \Kernel();
     *      echo $kernel->dump($arrayOne, $arrayTwo ...., $objectOne, $objectTwo... $arrayN, $objectN);
     *      exit;
     * </code>
     *
     * @return string
     */
    public function dump()
    {
        $objects = func_get_args();
        $content = "\n<pre>\n";
        foreach ($objects as $object) {
            $content .= print_r($object, true);
        }
        return $content."\n</pre>\n";
    }//end dump()

    /**
     * Adds or injects a dynamic method to the Kernel Class. Please note that
     * the method name must be unique.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->addMethod('customMethod', function($data, $render) use($user) {
     *          //The method logic goes here.
     *      });
     *
     *      //The newly added method can be later triggred as
     *      $kernel->customMethod($data, $render);
     * </code>
     *
     * @param string   $method   Name of the method to be added to the Kernel Class.
     * @param callback $callback Callback containung the logic of the method.
     *
     * @return boolean
     */
    public function addMethod($method, $callback)
    {
        $this->traceInternal("Added '".$method."' to ".__METHOD__."()");
        $this->callbacks[$method] = $callback;
        return true;
    }//end addMethod()

    public function customMethodExists($method)
    {
        if(isset($this->callbacks[$method])) {
            return true;
        }
        return false;
    }

    /**
     * Executes a dynamically defined/added method to the Kernel. If no such
     * method is found, then it throws an exception.
     *
     * @param callback $method The method called.
     * @param array    $args   The arguments passed to the method, given as array.
     *
     * @return mixed
     *
     * @throws \BadMethodCallException Throws exception, if method not defined or is not a valid callback.
     */
    public function __call($method, array $args)
    {
        if (isset($this->callbacks[$method]) && is_callable($this->callbacks[$method])) {
            $this->trace("Calling custom method '".$method."' added with ".__CLASS__."::addMethod()", $args);
            $callback = $this->callbacks[$method];
            return call_user_func_array($callback, $args);
        } else {
            throw new \BadMethodCallException("Method '".__CLASS__."::".$method."' not found or the command is not a valid callback type.");
        }
    }//end __call()

    /**
     * Sets callbacks which are triggred when a check on permission is done.
     * The calback should return true or false, based on if premision should be
     * granted or rejected respectively. If a callback has nothing to do with a
     * specific permission key, then it is expected to return true, in such a case.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->setPermission(function($permissionKey) use($user) {
     *          if ($permissionKey == 'can_add_user') {
     *              if(in_array($user->type, array('admin', 'manager'))) {
     *                  return true;
     *              }
     *              return false;
     *          }
     *          return true;
     *      });
     * </code>
     *
     * @param callback $callback The callback to be set as permission provider.
     *
     * @return boolean
     *
     * @throws \InvalidArgumentException Throws an exception if the arugument is not a valid callback.
     */
    public function setPermission($callback)
    {
        $hookName = md5(rand()).count($this->app_permission);
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("Parameter must be valid callback. Given (".gettype($callback).")");
        }
        $this->app_permission[$hookName] = $callback;
        return true;
    }//end setPermission()

    /**
     * Checks permission by running all the registered hooks. returns false if one of them
     * return's false else returns true.
     *
     * <code>
     *      $kernel = \Kernel();
     *      if ($kernel->checkPermission('can_add_user')==true) {
     *           //Permission Granted. So we create the new user.
     *           $user = \Model\User::create(array(
     *              'name'  => 'User Name',
     *              'email' => 'user@email.com'
     *          ));
     *      } else {
     *          //Permission denied. Set a error message
     *          $kernel->setMessage('Sorry, you do not have permission to add users', 'error');
     *      }
     * </code>
     *
     * @param string $value The permission key against which the permission will be validated.
     *
     * @return boolean
     * @throws \Exception
     */
    public function checkPermission($key, $user=false)
    {
        # Load loggedin user is $user is null
        if($user == false) {
            $user = \Model\Session::load_user();
        }
        # Return the value, if the specific permission is already loaded
        if (isset($this->user_permission[$user->id][$key])) {
            return $this->user_permission[$user->id][$key];
        }
        # Load the list of configurable permissions if not loaded
        if($this->user_permission_list == null) {
            $this->user_permission_list = $this->events('system')->filter('load_user_permission', array());
        }
        # Check if permission key exists in configurable permissions
        if(isset($this->user_permission_list[$key])) {
            try {
                $permission = \Model\Privilege::get_permission($key, $user);
            } catch(\Exception $e) {
                $permission = isset($this->user_permission_list[$key]['default'])?$this->user_permission_list[$key]['default']:null;
            }
            if (isset($this->user_permission_list[$key]['callback']) && is_callable($this->user_permission_list[$key]['callback'])) {
                $permission = (bool) call_user_func_array($this->user_permission_list[$key]['callback'], array($user, $this->request(), $permission));
            }
            $this->user_permission[$user->id][$key] = $permission;
            return $permission;
        }
        # if not found on user permission list, check on app permission
        if (count($this->app_permission) > 0) {
            foreach ($this->app_permission as $hookName => $callback) {
                try {
                    $response = call_user_func_array($callback, array($key, $user));
                } catch (\Exception $e) {
                    $response = $e;
                }
                if ($response === false) {
                    $this->user_permission[$user->id][$key] = false;
                    return false;
                }
            }
        }
        $this->user_permission[$user->id][$key] = true;
        return true;

    }//end checkPermission()

    /**
     * Converts and PHP array to JSON and returns the JSON data.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $array = $kernel->toJson(array('available' => true));
     * </code>
     *
     * @param array $array The array to be converted to JSON.
     *
     * @return string
     */
    public function toJson(array $array)
    {
        return str_replace("'", "", @json_encode($array));
    }//end toJson()

    /**
     * Converts an JSON text/data to PHP array and returns the array.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $array = $kernel->toArray($jsonData);
     * </code>
     *
     * @param string $jsontext The JSON data to be converted to array.
     *
     * @return array
     */
    public function toArray($jsontext)
    {
        return json_decode($jsontext, true);
    }//end toArray()

    /**
     * Sets a persistant system message to be returned by $kernel->getMessage()
     * Repeating a message id, before it is read, will overwrite the existing
     * message.
     *
     * Set an informative message:
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->setMessage('This is just an information');
     * </code>
     *
     * Set an warning message with heading:
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->setMessage('This is a warning', 'warn', 'Warning Heading');
     * </code>
     *
     * Set an error message with custom message id:
     * <code>
     *      $kernel = \Kernel();
     *      $kernel->setMessage('This is an error message', 'error', false, '5dsf46sd54fsd846');
     * </code>
     *
     * @param string         $message The message to be displayed to the user.
     * @param string         $type    Type of message. available options are "info", "warn" and "error".
     * @param string|boolean $heading Heading for the message or false for no heading.
     * @param string|boolean $msgid   A unique ID for the message. Set false to auto-generate.
     *
     * @return boolean
     */
    public function setMessage($message, $type = 'info', $heading = false, $msgid = false)
    {
        if ($msgid == false) {
            $msgid = md5($message);
        }
        $this->session()->set(
            'system_message',
            array(
                $msgid => array(
                    'content'   => $message,
                    'type'      => $type,
                    'heading'   => $heading,
                )
            )
        );
        return true;
    }//end setMessage()

    /**
     * Returns all the system messages, set by the application, wrapped in HTML.
     * This function also deletes the messages, which it has already returned,
     * so that messages are shown only once.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $message = $kernel->getMessage();
     *      echo $message;
     * </code>
     *
     * @return string|boolean
     */
    public function getMessage()
    {
        $message = $this->session()->get('system_message');
        if ($message == null) {
            return false;
        }
        $this->session()->destroy('system_message');
        $responce = '';
        foreach ($message as $msg) {
            $responce .= '<div class="alert alert-'.$msg['type'].'"><button type="button" class="close" data-dismiss="alert">×</button>';
            if ($msg['heading'] != false) {
                $responce .= '<h4 style="margin-bottom: 10px;">'.$msg['heading'].'</h4>';
            }
            $responce .= '<p>' . $msg['content'].'</p>';
            $responce.= '</div>';
        }

        return $responce;
    }//end getMessage()

    public function getMessageUnformatted()
    {
        $message = $this->session()->get('system_message');
        if ($message == null) {
            return false;
        }
        $this->session()->destroy('system_message');
        $responce = '';
        foreach ($message as $msg) {
            $responce = $msg;
            break;
        }
        return $responce;
    }//end getMessage()

    /**
     * Runs a cli commend and returns the output. In case the commend is ran as a
     * background process, then this function will return the process id.
     *
     * Running CLI Commend
     * <code>
     *      $kernel = \Kernel();
     *      $output = $kernel->runCLI('ls -la');
     *      echo $output; //echos the output of 'la -la'
     * </code>
     *
     * Running CLI Commend as background process.
     * <code>
     *      $kernel = \Kernel();
     *      $output = $kernel->runCLI('/path/to/script.sh', true);
     *      echo $output; //echos the PID of the process running the script.
     * </code>
     *
     * @param string  $request    The CLI commend to run.
     * @param boolean $background Set to true to run the process in the background.
     *
     * @return string
     */
    public function runCLI($request, $logFile='/dev/null', $background = true)
    {
        //_debug($request);

        if ($background == true) {
            exec($this->config('app.php.cli.exec').' '.$this->config('system.path.root').'/public/index.php '.APP_DOMAIN.' '.$request.' > '.$logFile.' 2>/dev/null  & echo ${!};', $output);
            return $output[0];
        } else {
            return exec($this->config('app.php.cli.exec').' '.$this->config('system.path.root').'/public/index.php '.APP_DOMAIN.' '.$request);
        }
    }//end runCLI()

    /**
     * This function queries gravatar.com and returns an image of the owner of
     * the email address, if available.
     *
     * Please note that this function, for now, uses a third party service
     * from http://gravatar.com to fetch information about the email address.
     *
     * <code>
     *      $kernel = \Kernel();
     *      $image = $kernel->getGravatar('someone@email.com');
     *      echo $image;
     * </code>
     *
     * @param string  $email Email address to be quired.
     * @param string  $s     Size of the requested image (gussing).
     * @param string  $d     Unknowen.
     * @param string  $r     Unknowen.
     * @param boolean $img   Unknowen.
     * @param array   $atts  Unknowen.
     *
     * @return string
     */
    public function getGravatar($email, $s = 110, $d = 'mm', $r = 'g', $img = false, array $atts = array())
    {
        $url  = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="'.$url.'"';
            foreach ($atts as $key => $val) {
                $url .= ' '.$key.'="'.$val.'"';
            }
            $url .= ' />';
        }

        return $url;
    }//end getGravatar()

    public function getUserImage($u, $use_gravatar=true)
    {
        $user = \Model\Consumer::find($u);
        $image_file = $this->config('app.consumer.image_path') . '/user-image-'.$user->id.'.png';
        if(file_exists($image_file)) {
            return '<img src="/userimage/'.$user->id.'.png" />';
        }
        $final_image_file = $this->config('app.consumer.final_image_path') . '/'.$user->id.'.png';
        if(file_exists($final_image_file)) {
            copy($final_image_file, $image_file);
            return '<img src="/userimage/'.$user->id.'.png" />';
        }
        if($use_gravatar == true) {
            return $this->getGravatar($user->email, 110, 'mm', 'g', true);
        }
        return null;
    }

    public function checkPHPSyntex($scrpitData)
    {
        $responce = array();
        $tmpFile = '/tmp/'.uniqid('HB').'.checksyntex.php';
        while(file_exists($tmpFile)) {
            $tmpFile = '/tmp/'.uniqid('HB').'.checksyntex.php';
        }
        $outputFile = $tmpFile.'.output';

        file_put_contents($tmpFile, $scrpitData);
        //$output = exec('/usr/bin/php -l ' . $tmpFile);

        $process = proc_open('sh', array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("file", $outputFile, "a") // stderr is a file to write to
        ), $pipes, '/tmp', array());

        if (is_resource($process)) {
            fwrite($pipes[0], '/usr/bin/php -l ' . $tmpFile);
            fclose($pipes[0]);
            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            proc_close($process);
        }

        unlink($tmpFile);
        unlink($outputFile);

        if(stripos($output, 'No syntax errors detected') !== false) {
            $responce['syntex_error']   = false;
        } else {
            $responce['syntex_error']   = true;
        }
        $responce['output'] = $output;
        return $responce;
    }

    function encrypt($string, $key) {

        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . '@vs-ss@' . $ciphertext;
        $ciphertext_base64 = base64_encode($ciphertext);
        //_debug($ciphertext_base64);
        //$this->decrypt($ciphertext_base64, $key);
        return $ciphertext_base64;
    }

    function decrypt($string, $key) {

        //$key = pack('H*', $key);
        $ciphertext_dec = base64_decode($string);
        $csegments = explode('@vs-ss@', $ciphertext_dec);
        if(count($csegments) != 2) {
            throw new \Exception('Decryption Error');
        }
        $decrypted_string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $csegments[1], MCRYPT_MODE_CBC, $csegments[0]);
        //_debug($decrypted_string);
        return $decrypted_string;
    }

    /**
     * Returns the detail infomation of an IP address, which are "hostname", "city",
     * "region" (State), "country", "location (Latitude and Longitude)", "origin",
     * and "Postal Code" (Zip Code).
     *
     * Please note that this function, for now, uses a third party service
     * from http://ipinfo.io to fetch information about any given IP address.
     *
     * As of now this function has only been tested with IPv4, and IPv6 is probably
     * not supported by ipinfo.io.
     *
     * <code>
     *     $kernel = \Kernel();
     *     $ipInfo = $kernel->getIpInfo('122.163.66.176');
     * </code>
     *
     * @param string  $ip    Ip address to get information for.
     * @param boolean $cache Set true to enable caching of fetched data, or else set false to disable.
     *
     * @return array
     */
    public function getIpInfo($ip, $cache = true)
    {
        if ($cache == true) {
            $container = dirname(dirname(__DIR__)).'/resources/'.APP_DOMAIN.'/cache';
            if (!file_exists($container)) {
                mkdir($container);
            }
            $cache_dir = $container .'/iploc';
            if (!file_exists($cache_dir)) {
                mkdir($cache_dir);
            }
            $cache_location = $cache_dir.'/'.$ip;
            if (file_exists($cache_location)) {
                return json_decode(file_get_contents($cache_location), true);
            }
        }
        $details = json_decode(file_get_contents("http://ipinfo.io/".$ip), true);
        if ($cache == true) {
            file_put_contents($cache_location, json_encode($details));
        }
        return $details;
    }//end getIpInfo()
}//end class
