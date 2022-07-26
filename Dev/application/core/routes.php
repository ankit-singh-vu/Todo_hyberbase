<?php
/**
 * $route.
 *
 * PHP version 5.5.9
 *
 * These are the default application route available to all applications running
 * on this platform.
 *
 * @category   Framework
 * @package    Route
 * @author     Dyutiman Chakraborty <dc@mclogics.com>
 * @copyright  2014 - 2015, HB
 * @license    https://licenses.app.HB.com/psl-1.0.txt Proprietary Service Licence ver. 1.0
 *
 * @link       https://framework.local
 */
$router->route('default', '/')
     ->defaults(array('module' => 'index', 'action' => 'index', 'format' => 'html'));


/*
$router->route('s2c_dashboard', '/dashboard')
    ->defaults(array('module' => 'index', 'action' => 'dashboard', 'format' => 'html'));

$router->route('s2c_plans', '/plans')
    ->defaults(array('module' => 'index', 'action' => 'plans', 'format' => 'html'));

$router->route('s2c_settings', '/settings')
    ->defaults(array('module' => 'index', 'action' => 'settings', 'format' => 'html'));
*/


$router->route('module_item_action', '/<:module>/<#item>/<:action>(.<:format>)') // Note:- :format optional.
    ->defaults(array('format' => 'html'));

$router->route('module_item', '/<:module>/<#item>(.<:format>)') // Note:- :format optional.
    ->defaults(array('action' => 'view', 'format' => 'html'))
    ->get(array('action' => 'view'))
    ->put(array('action' => 'put'))
    ->delete(array('action' => 'delete'));

$router->route('module_action', '/<:module>/<:action>(.<:format>)') // Note:- :format optional.
    ->defaults(array('format' => 'html'))
    ->post(array('action' => 'post'));

$router->route('module', '/<:module>(.<:format>)') // Note:- :format optional.
    ->defaults(array('action' => 'index', 'format' => 'html'))
    ->post(array('action' => 'post'));
