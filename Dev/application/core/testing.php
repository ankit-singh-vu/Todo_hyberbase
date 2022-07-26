<?php
/**
 * Defined Testing environment.
 * 
 * @package    Platform
 * @subpackage Env/Test
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  (c) 2014 - 2015, Sky
 * @license    https://licenses.domain.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 */

if(DEPLOYMENT_MODE == SAAS || DEPLOYMENT_MODE == CONTAINER) {
    // Save current directory as full path to www.
    define('SYSTEM_WEB_ROOT', realpath(__DIR__ . '/../public'));
    define('APP_DOMAIN', getenv('HB_TEST_APP_DOMAIN'));
    //define('APP_ENVIRONMENT', 'TEST');
}

if(DEPLOYMENT_MODE == APPLICATION) {
    // Save current directory as full path to www.
    define('SYSTEM_WEB_ROOT', realpath(__DIR__ . '/../public'));
    define('APP_DOMAIN', false);
    //define('APP_ENVIRONMENT', 'TEST');
}