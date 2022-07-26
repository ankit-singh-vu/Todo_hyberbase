<?php

namespace Model;
use ActiveRecord, System;
use Nette\Mail\Message;

class Socket extends \Plugin\System\Notification\Notification
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static $table_name = 'plugin_system_notification_socket';

    static $schema = array(
      "id"              => "bigint(250) NOT NULL AUTO_INCREMENT",
      "user_id"         => "bigint(250) NOT NULL DEFAULT '0'",
      "data"            => "text NOT NULL",
      "content"         => "text NOT NULL",
      "created_at"      => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
      "updated_at"      => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
      "send_at"         => "bigint(250) NOT NULL DEFAULT '0'",
      "expires_at"      => "bigint(250) NOT NULL DEFAULT '0'",
      "status"          => "int(1) NOT NULL DEFAULT '0'"
    );

    static public function send($params)
    {
        return self::send_socket_message($params['to'], $params);
    }

    /**
     * @param $to
     * @param $options
     * @param string $protocol
     * @return mixed
     * @throws \Exception
     */
    static public function send_socket_message($to, $options)
    {
        if(is_array($to)) {
            $notify = array();
            foreach($to as $send_to) {
                $notify[] = self::send_socket_message($send_to, $options);
            }
            return $notify;
        }

        $arguments = array();

        if(!$to instanceof \Model\User) {
            return false;
        }
        $arguments['user_id'] = $to->id;
        $arguments['data'] = json_encode(isset($options['vars'])?$options['vars']:array());

        if(!isset($options['template_location'])) {
            $template_path = \Kernel()->config('system.path.root')
                . '/' . \Kernel()->config('app.plugin.system.notification.template_location')
                . '/' . $options['template'];
        } else {
            $template_path = \Kernel()->config('system.path.root')
                . '/' . $options['template_location']
                . '/' . $options['template'];
        }


        if(!file_exists($template_path . '.html')) {
            throw new \Exception('Invalid template path');
        }
        $content['html'] = process_template(
            file_get_contents($template_path.'.html'),
            isset($options['variables'])?$options['variables']:array()
        );

        if(isset($options['image_uri'])) {
            $image_url_setings = $options['image_uri'];
        } else {
            $image_url_setings = \Kernel()->config('app.plugin.system.notification.image_uri');
        }
        $content['html'] = str_replace($image_url_setings[0], $image_url_setings[1], $content['html']);
        $arguments['content'] = base64_encode($content['html']);

        return self::create($arguments)->push();
    }

    public function push()
    {
        $now = date('U');
        if(\Kernel()->socket_message(\Model\User::find($this->user_id), 'widget_notify', array('content' => base64_decode($this->content)))) {
            $this->status = SOCKET_MESSAGE_STATUS_SEND;
            $this->send_at = $now;
            $this->expires_at = $now + (60*60*24*30);
            $this->save();
        }
        return $this;
    }



}





























