<?php
/**
 * Defined Production environment.
 * 
 * @package    Platform
 * @subpackage Env/Test
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

if(DEPLOYMENT_MODE == SAAS || DEPLOYMENT_MODE == CONTAINER) {
    define('SYSTEM_WEB_ROOT', realpath(__DIR__ . '/../public'));
    if (isset($_SERVER['HTTP_HOST'])) {
        define('APP_DOMAIN', $_SERVER['HTTP_HOST']);
    } else {
        if (!isset($GLOBALS['__HB_DOMAIN'])) {
            die('Container host domain can not be NULL');
        }
        define('APP_DOMAIN', $GLOBALS['__HB_DOMAIN']);
    }
    //define('APP_ENVIRONMENT', 'PRODUCTION');
}

if(DEPLOYMENT_MODE == APPLICATION) {
    define('SYSTEM_WEB_ROOT', realpath(__DIR__ . '/../public'));
    if (isset($_SERVER['HTTP_HOST'])) {
        define('APP_DOMAIN', $_SERVER['HTTP_HOST']);
    } else {
        if(isset($argv[1])) {
            define('APP_DOMAIN', $argv[1]);
        } else {
            define('APP_DOMAIN', false);
        }
    }
    //define('APP_ENVIRONMENT', 'PRODUCTION');
}