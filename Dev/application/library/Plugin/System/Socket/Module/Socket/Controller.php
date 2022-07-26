<?php

namespace Module\Socket;

class Controller extends \System\Module\ControllerAbstract
{


    public function scriptAction(\System\Request $request)
    {
        $this->view->domain = $request->env('COOKIE_DOMAIN');
        return $this->view;
    }

    public function postMethod(\System\Request $request)
    {
        $event = $request->post('event');
        $params = json_decode($request->post('params'), true);
        if (!isset($params['key'])) {
            return array('event' => 'terminate', 'data'  => array());
        }
        if($event != 'disconnected') {
             $session = \Model\Session::find_by_token($params['key']);
             if(!$session) {
                return array('event' => 'terminate', 'data'  => array());
                //return array();
             }
             $params['session'] = $session;
             try {
                 $connection = \Model\Connection::find_or_create_by_session_id_and_socket_session_and_socket(
                     $session->id, $params['socket_session'], $params['socket_ip']
                 );
             } catch(\Exception $e) {
                 //___debug($e);
                 return array('event' => 'terminate', 'data'  => array());
                 //return array();
             }
             if($connection->user_id == 0) {
                 $connection->user_id = $session->user_id;
                 $connection->connected_on = date('U');
                 $connection->save();
             }
            $params['connection'] = $connection;
        } else {
            try {
                $connection = \Model\Connection::find_by_socket_session_and_socket(
                    $params['socket_session'], $params['socket_ip']
                );
            } catch(\Exception $e) {
                return array('event' => 'terminate', 'data'  => array());
            }
            if($connection) {
                $connection->delete();
            }
        }
        //___debug($params);
        $this->kernel->events('socket')->trigger($event, array($params));
        return array();
    }
}




















