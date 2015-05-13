<?php

require_once __DIR__ . '/swiftmailer/swift_required.php';

class SwiftMailer
{
    private $_config;

    public function __construct($config)
    {
        $this->_config = $config;
    }

    public function send($dataMail, $attachments = array())
    {
        // Create the message
        $message = Swift_Message::newInstance()
            ->setTo($dataMail['to'])
            ->setFrom(array(
                $dataMail['from'] => $dataMail['from_name']
            ))
            ->setSubject($dataMail['subject'])
            ->setBody($dataMail['body'], 'text/html');

        $transport = Swift_SmtpTransport::newInstance(
                $this->_config['host'],
                $this->_config['port'],
                $this->_config['ssl']
            )
            ->setUsername($this->_config['username'])
            ->setPassword($this->_config['password']);
            
        // Create the Mailer using your created Transport
        $mailer = Swift_Mailer::newInstance($transport);

        return $mailer->send($message);
    }
}