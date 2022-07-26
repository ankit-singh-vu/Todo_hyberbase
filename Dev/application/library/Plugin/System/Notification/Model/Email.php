<?php

namespace Model;
use ActiveRecord, System;
use Nette\Mail\Message;

class Email extends \Plugin\System\Notification\Notification
{
    /**
     * Name of the table related to the User Model
     *
     * @var string
     */
    static string $table_name = 'plugin_system_notification_emails';

    static array $schema = array(
      "id"              => "bigint(250) NOT NULL AUTO_INCREMENT",
      "user_id"         => "bigint(250) NOT NULL DEFAULT '0'",
      "emailto"         => "text NOT NULL",
      "emailfrom"       => "text NOT NULL",
      "subject"         => "text NOT NULL",
      "data"            => "text NOT NULL",
      "content"         => "text NOT NULL",
      "content_text"    => "text NOT NULL",
      "created_at"      => "timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
      "updated_at"      => "timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'",
      "send_at"         => "bigint(250) NOT NULL DEFAULT '0'",
      "status"          => "int(1) NOT NULL DEFAULT '0'",
      "attachments"     => "text NOT NULL",
      "protocol"        => "text NOT NULL"
    );

    static public function send($params)
    {
        if(!isset($params['protocol'])) {
            $params['protocol'] = 'default';
        }
        return self::sendmail($params['to'], $params, $params['protocol']);
    }

    /**
     * @param $to
     * @param $options
     * @param string $protocol
     * @return mixed
     * @throws \Exception
     */
    static public function sendmail($to, $options, $protocol='default')
    {
        if(is_array($to)) {
            $notify = array();
            foreach($to as $send_to) {
                $notify[] = self::sendmail($send_to, $options, $protocol);
            }
            return $notify;
        }
        $arguments = array();
        if(!isset($options['subject'])) {
            throw new \Exception('Subject is required for sending mail');
        }
        if(!isset($options['template']) && !isset($options['template_path'])) {
            throw new \Exception('Mail template or is required for sending mail');
        }
        $arguments['emailfrom'] = \Kernel()->config('app.plugin.system.notification.sender', 'system@advisorlearn');
        if($to instanceof \Model\User) {
            $arguments['user_id'] = $to->id;
            $arguments['emailto'] = $to->email;
        } else {
            $arguments['emailto'] = $to;
        }
        $arguments['protocol'] = $protocol;
        $arguments['subject'] = $options['subject'];
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

        if(!file_exists($template_path . '.html') || !file_exists($template_path . '.txt')) {
            throw new \Exception('Invalid mail template path ('.$template_path.')');
        }
        $content['html'] = process_template(
            file_get_contents($template_path.'.html'),
            isset($options['variables'])?$options['variables']:array()
        );
        $content['txt'] = process_template(
            file_get_contents($template_path.'.txt'),
            isset($options['variables'])?$options['variables']:array()
        );

        if(isset($options['image_uri'])) {
            $image_url_setings = $options['image_uri'];
        } else {
            $image_url_setings = \Kernel()->config('app.plugin.system.notification.image_uri');
        }
        $content['html'] = str_replace($image_url_setings[0], $image_url_setings[1], $content['html']);

        $arguments['content'] = $content['html'];
        $arguments['content_text'] = $content['txt'];

        if(isset($options['attachments']) && is_array($options['attachments'])) {
            $arguments['attachments'] = json_encode($options['attachments']);
        } else {
            $arguments['attachments'] = json_encode(array());
        }
        //return self::create($arguments)->push();
        return self::create($arguments)->push_to_sendgrid_api();
    }

    public function push()
    {
        if(\Kernel()->config('app.mode.development') === false) {
            if (!\Kernel()->request()->isCli()) {
                \Kernel()->runCLI('exsend/' . $this->id . '/process');
                return $this;
            }
        }
        $mail = new Message;
        $mail->setFrom($this->emailfrom);
        $mail->setSubject($this->subject);
        $mail->addTo($this->emailto);

        $mail->setHTMLBody($this->content);
        $mail->setBody($this->content_text);

        $attachments = json_decode($this->attachments, true);
        foreach($attachments as $attachment) {
            if(file_exists($attachment)) {
                $mail->addAttachment($attachment);
            }
        }
        $mailer = new \Nette\Mail\SmtpMailer(
            \Kernel()->config('app.plugin.system.notification.protocols.'.$this->protocol)
        );
        $this->send_at = date('U');
        $mailer->send($mail);
        $this->status = 1;
        $this->save();
        return $this;
    }

    public function push_to_sendgrid_api()
    {
        if(\Kernel()->config('app.mode.development') === false) {
            if (!\Kernel()->request()->isCli()) {
                \Kernel()->runCLI('exsend/' . $this->id . '/process');
                return $this;
            }
        }

        $email = new \SendGrid\Mail\Mail();

        $email->setFrom($this->emailfrom);
        $email->setSubject($this->subject);
        $email->addTo($this->emailto);

        $email->addContent("text/plain", $this->content_text);
        $email->addContent("text/html", $this->content);
        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

        return $sendgrid->send($email);

    }



}





























