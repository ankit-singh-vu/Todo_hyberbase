<?php
/**
 * Framework Entry Point..
 * 
 * @package    Platform
 * @subpackage EntryPoint
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

//phpinfo(); exit;

$root_directory = dirname(__DIR__);

//Load System Definations
require $root_directory . '/core/definitions.php';

// If CLI call the capture domain.
if (DEPLOYMENT_MODE == SAAS && isset($argv[1])) {
    $GLOBALS['__HB_DOMAIN'] = $argv[1];
}

// Load Production Environment.
require dirname(__DIR__) . '/core/switch.php';

// Require app init (inital framework setup).
require dirname(__DIR__) . '/core/init.php';

$appPath = $kernel->config('app.path.root');
if(file_exists($appPath . '/definitions.php')) {
    require $appPath . '/definitions.php';
}

if(defined('FORCE_HTTPS_ACCESS') && FORCE_HTTPS_ACCESS == true) {
    if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] != 'https') {
        header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit;
    }
}

// Run Application.
try {
    // Global setup based on config settings.
    date_default_timezone_set($kernel->config('app.i18n.timezone', 'America/Chicago'));

    // Error and session setup.
    set_error_handler(array($kernel, 'errorHandler'));
    ini_set("session.cookie_httponly", true); // Mitigate XSS javascript cookie attacks for browers that support it.
    ini_set("session.use_only_cookies", true); // Don't allow session_id in URLs.
    session_set_cookie_params($kernel->config('app.session.lifetime'), "/");
    session_start();

    // Load plugins.
    if ($plugins = $kernel->config('app.plugins', false)) {
        if (!is_array($plugins)) {
            throw new \InvalidArgumentException(
                "Plugin configuration from app config must be an array. Given (" . gettype($plugins) . ")."
            );
        }
        foreach ($plugins as $pluginName) {
            $plugin = $kernel->plugin($pluginName);
        }
    }
    $kernel->events('system')->trigger('system_plugins_loaded');

    // Trigger boot_start.
    $kernel->events('system')->trigger('boot_start');
    //___debug($kernel);

    // Initial response code (not sent to browser yet).
    $responseStatus = 200;
    $response       = $kernel->response($responseStatus);
        
    // Router - Add routes we want to match.
    $router = $kernel->router();

    // Include Routes.
    include $kernel->config('system.path.root') . '/core/routes.php';
    $kernel->events('system')->trigger('routes_loaded', array($router));

    // Handle both HTTP and CLI requests.
    $request = $kernel->request();
    
    if ($request->isCli()) {
        $cliArgs          = $argv;
        $requestUrl       = isset($cliArgs[2]) ? $cliArgs[2] : '/';
        $qs               = parse_url($requestUrl, PHP_URL_QUERY);
        $cliRequestParams = array();
        parse_str($qs, $cliRequestParams);
        
        // Set parsed query params back on request object.
        $request->setParams($cliRequestParams);
        
        // Set requestUrl and remove query string if present so router can parse it as expected.
        if ($qsPos = strpos($requestUrl, '?')) {
            $requestUrl = substr($requestUrl, 0, $qsPos);
        }
    } else {
        // HTTP request.
        $requestUrl = isset($_GET['u']) ? $_GET['u'] : '/';
    }
    
    // Router - Match HTTP request and return named params.
    $requestMethod = $request->method();

    $params        = $router->match($requestMethod, $requestUrl);

    // Set matched params back on request object.
    $request->setParams($params);
    $request->route = $router->matchedRoute()->name();
    
    // Required params.
    $content = false;
    if (isset($params['module']) && isset($params['action'])) {
        $request->module = $params['module'];
        $request->action = $params['action'];
        
        // Matched route.
        $kernel->events('system')->trigger('route_match');

        // Run/execute.
        $content = $kernel->dispatchRequest($request->module, $request->action);
    } else {
        $content = $kernel->events('system')->filter('route_not_found', $content);
    }
    
    // Raise 404 error on boolean false result.
    if (false === $content) {
        throw new \System\Exception\FileNotFound(
            "Requested file or page not found. Please check the URL and try again."
        );
    }

    // Run resulting content through filter.
    $content = $kernel->events('system')->filter('dispatch_content', $content);

    // Explicitly convert response to string so Exceptions won't get caught in __toString method.
    if ($content instanceof System\Module\Response) {
        $responseStatus = $content->status();
        $content        = $content->content();
    } else {
        // Use explicitly set status code.
        $responseStatus = $kernel->response()->status();
    }
   
} catch (\System\Exception\Auth $e) {
    // Authentication Error.
    $responseStatus = 403;
    $e->setCode($responseStatus);
    $content        = $e;
} catch (\ActiveRecord\RecordNotFound $e) {
    // DB ActiveRecord Record Not Found.
    $responseStatus = 404;
    $e->setCode($responseStatus);
    $content        = $e;
    
} catch (\System\Exception\FileNotFound $e) {
    // Not Found 404 Errors.
    $responseStatus = 404;
    $e->setCode($responseStatus);
    $content        = $e;

} catch (\System\Exception\InstanceNotFound $e) {
    // Instance not found 500 Errors.
    $responseStatus = 500;
    $e->setCode($responseStatus);
    $content        = $e;
    
} catch (\System\Exception\Method $e) {
    // Method Not Allowed.
    $responseStatus = 405;
    $e->setCode($responseStatus);
    $content        = $e;

} catch (\System\Exception\Http $e) {
    // HTTP Exception.
    $responseStatus = $e->getCode();
    $e->setCode($responseStatus);
    $content        = $e;

} catch (\System\Exception $e) {
    // Module/Action Error.
    $responseStatus = 500;
    $e->setCode($responseStatus);
    $content        = $e;

} catch (\Exception $e) {
    // Generic Error.
    $responseStatus = 500;
    //$e->setCode($responseStatus);
    $content        = $e;
}//end try

// Exception detail depending on mode.
if ($content instanceof \Exception) {
    // Filter to give a chance for Plugins to handle error.
    $content = $kernel->events('system')->filter('dispatch_exception', $content);

    // Content still an exception, default display.
    if ($content instanceof \Exception) {
        $e = $content;
        // Resource object.
        $content = $kernel->resource(
            array(
                'exception' => array(
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                )
            )
        );
    }
}//end if


// Send proper response.
if ($kernel) {
    $response = $kernel->response();
    
    // Set content and send response.
    $response->status($responseStatus);
    $response->content($content);
    $response->send();
    
    // Debugging on?
    if ('html' == $kernel->request()->format && $kernel->config('app.debug')) {
        echo "<hr />";
        echo "<h2>Event Trace</h2>";
        echo $kernel->dump($kernel->trace());
    }

    // Notify that response has been sent.
    $kernel->events('system')->trigger('response_sent');

    // Notify events of shutdown.
    $kernel->events('system')->trigger('boot_end');
} else {
    header("HTTP/1.0 500 Internal Server Error");
    echo "<h1>Internal Server Error</h1>";
    echo $content;
}//end if
