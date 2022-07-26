<?php

/**
 * @package     :HyperBase
 * @subpackage  :System
 * @version     :7.0
 * @author      :Dyutiman Chakraborty <dc@mclogics.com>
 */

namespace Plugin\System\Socket;
use System, RuntimeException;

/**
 * Socket Plugin
 *
 * This plugin allows the application to communiate with the client's browser/app using
 * a web-socket.
 *
 * @OK_TESTED
 */
class Plugin
{
    protected $kernel;

    /**
     * Initialize plguin
     */
    public function __construct(System\Kernel $kernel)
    {
        $kernel->loader()->registerNamespace('Model', __DIR__);
        $kernel->loader()->registerNamespace('Module', __DIR__);

        $kernel->events('ui')->addFilter('load_script', 'plugin_system_socket', function($scripts) {
            if(!isset($scripts['https://'.getenv('COOKIE_DOMAIN').':8443/socket.io/socket.io.js'])) {
                $scripts['https://'.getenv('COOKIE_DOMAIN').':8443/socket.io/socket.io.js'] = array();
            }
            if(!isset($scripts['socket/script.js'])) {
                $scripts['socket/script.js'] = array();
            }
            return $scripts;
        });

        $kernel->addMethod('socket_broadcast', function($event, array $params) use($kernel) {
            $send = false;
            $relayed_ips = array();
            foreach(\Model\Connection::all() as $connection) {
                if(!isset($relayed_ips[$connection->socket])) {
                    $relayed_ips[$connection->socket] = 1;
                    $socket_url = 'http://' . $connection->socket . ':8080';
                    $client = new \System\Client(function ($data) {
                        return json_decode($data, true);
                    });
                    $client->post($socket_url, array(
                        'event' => $event,
                        'data' => json_encode($params)
                    ));
                    $send = true;
                }
            }
            return $send;
        });

        $kernel->addMethod('socket_message', function(\Model\User $user, $event, array $params) use($kernel) {
            $persistent = false;
            if(isset($params['_meta']['persistent']) && $params['_meta']['persistent'] == true) {
                $persistent = true;
            }
            unset($params['_meta']);
            if($persistent == true) {
                $message = \Model\Socket::create(array(
                    "user_id"     => $user->id,
                    "event"       => $event,
                    "data"        => json_encode($params)
                ));
            }
            $session = $user->get_session();
            if(!$session) {
                $kernel->events('socket')->trigger('socket_offline', array(
                    'user'  =>  $user, 'event' => $event, 'data'  => $params
                ));
                return false;
            }
            if($session->access->access_type != ACCESS_TYPE_WEB) {
                $kernel->events('socket')->trigger('socket_offline', array(
                    'user'  =>  $user, 'event' => $event, 'data'  => $params
                ));
                return false;
            }

            $relayed_ips = array();
            $send = false;
            foreach(\Model\Connection::find_all_by_session_id($session->id) as $connection) {
                if(!isset($relayed_ips[$connection->socket])) {
                    $relayed_ips[$connection->socket] = 1;
                    $socket_url = 'http://' . $connection->socket . ':8080';
                    $client = new \System\Client(function ($data) {
                        return json_decode($data, true);
                    });
                    $client->post($socket_url, array(
                        'access_token' => $session->token,
                        'event' => $event,
                        'data' => json_encode($params)
                    ));
                    $send = true;
                }
            }
            if($persistent == true && $send == true) {
                $message->send = 1;
                $message->save();
            }
            return $send;
        });

        return true;
    }

}