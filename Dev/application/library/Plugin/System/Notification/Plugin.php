<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Notification;
use System, RuntimeException;

define('NOTIFICATION_TYPE_EMAIL',  'Email');
define('NOTIFICATION_TYPE_SLACK',  'Slack');
define('NOTIFICATION_TYPE_SOCKET', 'Socket');

define('SOCKET_MESSAGE_STATUS_CREATED', 0);
define('SOCKET_MESSAGE_STATUS_SEND', 1);
define('SOCKET_MESSAGE_STATUS_READ', 2);

/**
 * Notification Plugin
 *
 * Allows the application to send notifications using channels like email, slack...etc
 * @todo: The current implimentaion only supports email, need to fix it.
 *
 * <code>
 *  \Kernel()->notify(array(
        NOTIFICATION_TYPE_EMAIL => array(...)
 * ));
 * </code>
 */
class Plugin
{
    protected $kernel;

    /**
     * Initialize plugin
     */
    public function __construct(System\Kernel $kernel)
    {
        $kernel->loader()->registerNamespace('Model', __DIR__);
        $kernel->loader()->registerNamespace('Module', __DIR__);

        $kernel->addMethod('notify_exception', function(\Exception $e) use($kernel) {
            if($kernel->config('app.plugin.system.notification.exceptions.notify', false) == true) {

            }
        });

        $kernel->addMethod('notify', function($ntype) use($kernel) {
            foreach($ntype as $driver => $options) {
                $nClass = '\\Model\\' . $driver;
                $nClass::send($options);
            }
        });

        return true;
    }


}