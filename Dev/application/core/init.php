<?php
/**
 * Init
 *
 * PHP version 5.5.9
 *
 * This file loads the configurations and bootstraps the framework, and prepairs
 * it's autolader. Once this script is executed the framework is ready for
 * loading enabled plugins and Modules.
 *
 * @category   Framework
 * @package    BaseConfig
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  2014 - 2015, HB
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 *
 * @link       https://framework.local
 */

// Show all errors by default - they can be turned off later if needed.
ini_set('display_errors', 'On');
error_reporting(-1);

require __DIR__.'/rb.php';
require __DIR__.'/functions.php';
require __DIR__.'/saas.php';


// PHP version must be 7.4 or greater.
if (version_compare(phpversion(), '7.4', '<')) {
    throw new \RuntimeException(
        "PHP version must be 7.4 or greater to run HyperBase.<br />\nCurrent PHP version: ".phpversion()
    );
}

// Configuration settings.
$cfgPath = dirname(__DIR__).'/resources';

// Host-based config file for overriding default settings in different environments.
$cfg = array();

if (DEPLOYMENT_MODE == SAAS || DEPLOYMENT_MODE == CONTAINER) {
    /*
    $cfgHostFile = $cfgPath.'/'.strtolower(APP_DOMAIN).'/config.php';
    if (!file_exists($cfgHostFile)) {
        if(!build_domain_resource(APP_DOMAIN)) {
            $cfgHostFile = $cfgPath.'/_master/config.php';
        }
    }*/
    $cfgHostFile = \SAAS::load_instance($cfgPath);
} else {
    $cfgHostFile = $cfgPath.'/config.php';
}

//_debug($cfgHostFile);

if (file_exists($cfgHostFile)) {
    // Host-based config file.
    $cfg = include __DIR__.'/platform.php';
    $system = $cfg['system'];

    // Request URL from .htaccess or query string.
    $requestUrl = '/'.(isset($_GET['u']) ? $_GET['u'] : '');
    $requestPath = parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
    $urlBase = $requestPath;

    // Replace last occurance of request URL in full path if found.
    $pathPos = strpos($requestPath, $requestUrl);
    if ($requestUrl != '/' && false !== $pathPos) {
        $urlBase = substr_replace($urlBase, '', $pathPos, strlen($requestUrl));
    }

    // URL info.
    $isHttps = (!isset($_SERVER['HTTPS']) || strtolower($_SERVER['HTTPS']) != 'on') ? false : true;
    $urlHost = (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');

    // URL Config values.
    $cfg['url']['root'] = 'http'.(($isHttps) ? 's' : '').'://'.$urlHost.''.rtrim($urlBase, '/').'/';
    $cfg['url']['assets'] = $cfg['url']['root'].'assets';
    include $cfgHostFile;
    $cfg = array_merge($cfg, array('app' => $app));
    $cfg['url']['rewrite'] = $cfg['app']['url']['rewrite'];

    // Directories (from install root).
    if (!isset($cfg['app']['dir'])) {
        $cfg['app']['dir']['root'] = '/';

        if (DEPLOYMENT_MODE == SAAS) {
            $cfg['app']['dir']['public'] = $cfg['app']['dir']['root'].'applications/'.$cfg['app']['name'].'/public';
        } else {
            if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == ADMIN_DOMAIN) {
                $cfg['app']['dir']['public'] = $cfg['app']['dir']['root'] . 'application/admin/public';
            } else if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == REGISTRATION_DOMAIN) {
                $cfg['app']['dir']['public'] = $cfg['app']['dir']['root'] . 'application/registration/public';
            } else {
                $cfg['app']['dir']['public'] = $cfg['app']['dir']['root'] . 'application/public';
            }
        }
        $cfg['app']['dir']['www'] = $cfg['app']['dir']['root'].'public';
        $cfg['app']['dir']['assets'] = $cfg['app']['dir']['www'].'assets';

        if (DEPLOYMENT_MODE == SAAS) {
            $cfg['app']['dir']['lib'] = $cfg['app']['dir']['root'].'applications/'.$cfg['app']['name'].'/lib';
            $cfg['app']['dir']['layouts'] = $cfg['app']['dir']['root'].'applications/'.$cfg['app']['name'].'/layouts';
        } else {
            if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == ADMIN_DOMAIN) {
                $cfg['app']['dir']['lib'] = $cfg['app']['dir']['root'] . 'application/admin/lib';
                $cfg['app']['dir']['layouts'] = $cfg['app']['dir']['root'] . 'application/admin/layouts';
            } else if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == REGISTRATION_DOMAIN) {
                $cfg['app']['dir']['lib'] = $cfg['app']['dir']['root'] . 'application/registration/lib';
                $cfg['app']['dir']['layouts'] = $cfg['app']['dir']['root'] . 'application/registration/layouts';
            } else {
                $cfg['app']['dir']['lib'] = $cfg['app']['dir']['root'] . 'application/lib';
                $cfg['app']['dir']['layouts'] = $cfg['app']['dir']['root'] . 'application/layouts';
            }
        }
    }

    // Full root paths.
    if (!isset($cfg['app']['path'])) {
        if (DEPLOYMENT_MODE == SAAS || DEPLOYMENT_MODE == CONTAINER) {
            $cfg['app']['path']['data'] = $cfgPath.'/'.strtolower(APP_DOMAIN).'/data';
        } else {
            $cfg['app']['path']['data'] = $cfgPath.'/data';
        }
        if (DEPLOYMENT_MODE == SAAS) {
            $cfg['app']['path']['root'] = dirname(__DIR__) .'/applications/'.$cfg['app']['name'];
        } else {
            if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == ADMIN_DOMAIN) {
                $cfg['app']['path']['root'] = dirname(__DIR__) . '/application/admin';
            } else if (DEPLOYMENT_MODE == CONTAINER && APP_DOMAIN == REGISTRATION_DOMAIN) {
                $cfg['app']['path']['root'] = dirname(__DIR__) . '/application/registration';
            } else {
                $cfg['app']['path']['root'] = dirname(__DIR__) . '/application';
            }
        }
        $cfg['app']['path']['core'] = dirname(__DIR__) . '/core';
        $cfg['app']['path']['themes'] = $cfg['app']['path']['root'].'/themes';
        $cfg['app']['path']['lib'] = $cfg['app']['path']['root'].'/lib';
        $cfg['app']['path']['layouts'] = $cfg['app']['path']['root'].'/layouts';
    }

    // Autoload paths.
    if (!isset($cfg['app']['autoload'])) {
        $cfg['app']['autoload'] = array(
            'namespaces' => array(
                'System' => $system['path']['lib'],
                'App' => array($cfg['app']['path']['lib'], $system['path']['lib']),
                'Module' => array($cfg['app']['path']['root']),
                'Plugin' => array($cfg['app']['path']['root'], $system['path']['lib']),

                'Platform' => array($cfg['system']['path']['root'] . '/catalog'),
                'Service'  => array($cfg['system']['path']['root'] . '/catalog')

            ),
            'prefixes' => array(
                // NOT_IN_USE: 'Zend_' => $cfg['system']['path']['lib'] // For loading Zend 1.X or lower.
            ),
        );
        if (isset($cfg['app']['__autoload'])) {
            if (isset($cfg['app']['__autoload']['namespaces'])) {
                $cfg['app']['autoload']['namespace'] = array_merge(
                    $cfg['app']['autoload']['namespace'],
                    $cfg['app']['__autoload']['namespace']
                );
            }
            if (isset($cfg['app']['__autoload']['prefixes'])) {
                $cfg['app']['autoload']['prefixes'] = array_merge(
                    $cfg['app']['autoload']['prefixes'],
                    $cfg['app']['__autoload']['prefixes']
                );
            }
        }
    }//end if
} else {
    //_debug('SYSTEM ERROR :: UNKNOWEN DOMAIN INSTANCE');
    include_once dirname(__DIR__) . '/public/assets/static/unknowen.domain.php';
    exit;
}//end if

// Ensure at least a lib path is set for both system and app.
if (!isset($cfg['system']['path']['lib']) || !isset($cfg['app']['path']['lib'])) {
    throw new \InvalidArgumentException(
        "Configuration must have at least \$cfg['system']['path']['lib'] and \$cfg['app']['path']['lib']"
        . "set in order to load required classes."
    );
}

// Load Kernel.
try {
    // Get Kernel with config and host config.
    include $cfg['system']['path']['lib'].'/System/Kernel.php';
    $kernel = \Kernel($cfg);
    unset($cfg);

    // Class autoloaders - uses PHP 5.3 SplClassLoader.
    $loader = $kernel->loader();

    // Register classes with namespaces.
    $loader->registerNamespaces($kernel->config('app.autoload.namespaces', array()));

    // Register a library using the PEAR naming convention.
    $loader->registerPrefixes($kernel->config('app.autoload.prefixes', array()));

    // Activate the system autoloader.
    $loader->register();

    //Activate the Auto loader for php composer
    if (file_exists(dirname(__DIR__) .'/vendor/autoload.php')) {
        require_once dirname(__DIR__) .'/vendor/autoload.php';
    }

    // Activate Composer Libraries autoloader.
    $loader->registerComposerLibraries();

    // Development Mode & Debug Handling.
    if ($kernel->config('app.mode.development')) {
        error_reporting(-1);
        //error_reporting(E_ALL);
        ini_set("display_errors", 1);
        if ($kernel->config('app.debug')) {
            // Enable debug mode.
            $kernel->debug(true);
        }
    } else {
        // Show NO errors.
        error_reporting(0);
        ini_set('display_errors', 'Off');
    }
} catch (\Exception $e) {
    echo '<pre>';
    echo $e->getTraceAsString();
    echo '</pre>';
    exit();
}//end try
