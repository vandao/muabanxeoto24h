<?php

class Mindrill {
    private $api_key  = '';
    private $base     = 'http://mandrillapp.com/api/1.0';
    private $suffix   = '.json';

    /**
     * API Constructor. If set to test automatically, will return an Exception if the ping API call fails
     *
     * @param string $key API Key
     * @param bool $test=true Whether to test API connectivity on creation
     */
    public function __construct($apiKey, $test = false)
    {
        $this->api_key = $apiKey;

        if ($test === true && !$this->test()) {
            throw new Exception('Cannot connect or authentice with the Mandrill API');
        }
    }

    /**
     * Send email
     * @param array $dataMail array(EmailFrom, EmailFromName, EmailTo, EmailCc, EmailBcc, EmailSubject, EmailBody)
     * @param array $attachments array(0 => array(FileName, File))
     * @return bool
     */
    public function send($dataMail, $attachments = array()) {
        $data = array(
            'html'           => $dataMail['EmailBody'],
            'text'           => '',
            'subject'        => $dataMail['EmailSubject'],
            'from_email'     => $dataMail['EmailFrom'],
            'to'             => array(
                array(
                    'email'  => $dataMail['EmailTo'],
                    'name'   => '',
                ),
            )
        );

        if (isset($dataMail['EmailFromName'])) $data['from_name']   = $dataMail['EmailFromName'];
        if (isset($dataMail['EmailCc']))       $data['bcc_address'] = $dataMail['EmailCc'];
        if (isset($dataMail['EmailBcc']))      $data['bcc_address'] = $dataMail['EmailBcc'];

        if (sizeof($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $pathInfo = pathinfo($attachment['FileName']);

                $data['attachments'][] = array(
                    'type'      => $pathInfo['extension'],
                    'name'      => $attachment['FileName'],
                    'content'   => $attachment['File'],
                );
            }
        }

        return $this->call('/messages/send', array('message' => $data)) === 'PONG!';
    }

    /**
     * Perform an API call.
     *
     * @param string $url='/users/ping' Endpoint URL. Will automatically add '.json' if necessary (both '/users/ping' and '/users/ping.jso'n are valid)
     * @param array $params=array() An associative array of parameters
     *
     * @return mixed Automatically decodes JSON responses. If the response is not JSON, the response is returned as is
     */
    public function call($url='/users/ping', $params=array()){
        if (is_null($params))
        {
            $params = array();
        }

        $url = strtolower($url);

        if (substr_compare($url, $this->suffix, -strlen($this->suffix), strlen($this->suffix)) !== 0)
        {
            $url .= '.json';
        }

        $params = array_merge($params, array('key'=>$this->api_key));

        $json = json_encode($params);

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, "{$this->base}{$url}");
        curl_setopt($ch,CURLOPT_POST,count($params));
        curl_setopt($ch,CURLOPT_POSTFIELDS,$json);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($json)));

        $result = curl_exec($ch);
        curl_close($ch);
        $decoded = json_decode($result);

        return is_null($decoded) ? $result : $decoded;

    }

    /**
     * Tests the API using /users/ping
     *
     * @return bool Whether connection and authentication were successful
     */
    public function test() {
        return $this->call('/users/ping') === 'PONG!';
    }
}