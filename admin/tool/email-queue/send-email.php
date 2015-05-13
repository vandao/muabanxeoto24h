<?php

include "../cli-bootstrap.php";

new Run($di);

class Run {
    private $_mail;
    private $_db;
    private $_defaultInfo;
    private $_emailAccount;

    const LIMIT_SENT_EMAIL_PER_DAY = 10;

    private $_providers = array(
        'gmail' => array(
            'config' => array(
                'host'     => 'smtp.gmail.com',
                'port'     => 587,
                'auth'     => 'login',
                'ssl'      => 'tls',
            ),
            'account-key' => 'Sendmail-Gmail-Account'
        ),
        'mandrill' => array(
            'config' => array(
                'host'     => 'smtp.mandrillapp.com',
                'port'     => 25,
                'auth'     => 'login',
                'ssl'      => 'tls',
            ),
            'account-key' => 'Sendmail-Gmail-Mandrill'
        ),
        'amazon-ses' => array(
            'config' => array(
                'host'     => 'email-smtp.us-east-1.amazonaws.com',
                'port'     => 587,
                'auth'     => 'login',
                'ssl'      => 'tls',
            ),
            'account-key' => 'Sendmail-Gmail-Amazon-SES'
        ),
    );

    /**
     * Get all email queues and send it
     */
    public function __construct($di) {
        $this->_db            = $di->getShared('dbEmailQueue');
        $this->_systemConfig  = $di->getShared('systemConfig');
        $this->_defaultInfo   = json_decode($this->_systemConfig['Sendmail-From'], true);
        $this->_setEmailAccount();
        // print_r($this->_emailAccount);exit;


        while(1) {
            $emailQueue = EmailQueue::findFirst(array(
                "conditions" => "date_sent IS NULL AND status = ?1",
                "bind"       => array(
                    1 => STATUS_PENDING,
                ),
                "order" => "priority DESC",
            ));
            if (! $emailQueue) break;

            $isSend = true;
            if ($emailQueue->has_daily_send_limit) {
                if ($this->_getNumberOfEmailSentTodayByEmail($emailQueue->to) >= self::LIMIT_SENT_EMAIL_PER_DAY) {
                    $isSend = false;
                }
            }
            if ($isSend) {
                /**
                 * Update SENDING Status for email queue row
                 */
                if ($this->_updateSendingStatus($emailQueue)) {
                    $this->_send($emailQueue->toArray());
                }
            } else {
                $this->_updateEmailQueueResult($emailQueue->toArray(), "Over sent 10 email per day", STATUS_ERROR);
            }
        }
    }

    /**
     * Update sending status for all email queues fetch out for send
     * @param array $emailQueues
     * @return bool
     */
    private function _updateSendingStatus($emailQueue) {
        try {
            $this->_db->begin();
            if ($emailQueue->status == STATUS_PENDING) {
                $query  = "UPDATE email_queue SET status = '" . STATUS_SENDING . "' WHERE id = '{$emailQueue->id}'";
                $affectedRows = $this->_db->query($query);

                if ($affectedRows->numRows() === 1) {
                    $this->_db->commit();
                    return true;
                }
            }
        } catch (Exception $e) {
            $this->_db->rollback();
            die($e->getMessage());
        }

        $this->_db->commit();
        return false;
    }

    /**
     * Simple send Email
     * @param array $dataMail
     */
    private function _send($dataMail = array()) {
        $defaultInfo   = $this->_defaultInfo;
        $emailAccount  = $this->_emailAccount;
        // var_dump($emailAccount);exit;

        if ($dataMail['from_name'] == "" && isset($defaultInfo['from_name'])) $dataMail['from_name'] = $defaultInfo['from_name'];
        if ($dataMail['from'] == "" && isset($defaultInfo['from']))           $dataMail['from']      = $defaultInfo['from'];
        if ($dataMail['reply_to'] == "" && isset($defaultInfo['reply_to']))   $dataMail['reply_to']  = $defaultInfo['reply_to'];
        if ($dataMail['cc'] == "" && isset($defaultInfo['cc']))               $dataMail['cc']        = $defaultInfo['cc'];
        if ($dataMail['bcc'] == "" && isset($defaultInfo['bcc']))             $dataMail['bcc']       = $defaultInfo['bcc'];

        try {
            /**
             * Checking if has attachments we will send email via google
             * ELSE we will using our system
             */
            if ($emailAccount['provider'] == 'mandrill') {
                include_once('services/mindrill.php');

                $mandrill = new Mindrill($emailAccount['username']);
                $mandrill->send($dataMail, $this->_getAttachments($dataMail['id']));
                $sendResults = STATUS_SENT;

            } elseif ($emailAccount['provider'] == 'gmail') {
                include_once('services/swiftmailer.php');

                $swift = new SwiftMailer($emailAccount);
                $swift->send($dataMail, $this->_getAttachments($dataMail['id']));
                $sendResults = STATUS_SENT;

            } elseif ($emailAccount['provider'] == 'amazon-ses') {
                include_once('services/amazone-ses.php');

                $amazonSes = new SimpleEmailService($emailAccount['username'], $emailAccount['password']);

                $sesMessage = new SimpleEmailServiceMessage();
                $sesMessage->addTo($dataMail['to']);
                $sesMessage->setFrom($dataMail['from']);
                $sesMessage->setSubject($dataMail['subject']);
                $sesMessage->setMessageFromString('', $dataMail['body']);

                if ($dataMail['reply_to'] != "") $sesMessage->addReplyTo($dataMail['reply_to']);
                if ($dataMail['cc'] != "")       $sesMessage->addCC($dataMail['cc']);
                if ($dataMail['bcc'] != "")      $sesMessage->addBCC($dataMail['bcc']);

                $responses = $amazonSes->sendEmail($sesMessage);

                if (isset($responses['message_id']) && $responses['message_id'] != '') {
                    $sendResults = STATUS_SENT;
                } else {
                    $sendResults = $responses['error']['message'];
                }

            } else {
                $this->_mail->send();
                $sendResults = STATUS_SENT;
            }

            if ($sendResults == STATUS_SENT) {
                $queueStatus = STATUS_SENT;
                $return = true;
            } else {
                $queueStatus = STATUS_ERROR;
                $return = false;
            }
        } catch(Exception $e) {
            $sendResults = $e->getMessage();
            $queueStatus = STATUS_ERROR;
            $return = false;
        }

        // $sendResults = 'test';
        // $queueStatus = STATUS_ERROR;
        // $return = false;

        if ($return) {
            $dateSent = date("Y-m-d H:i:s", time());
        } else {
            $dateSent = "";
        }

//        print_r($data);exit;
        if ($this->_updateEmailQueueResult($dataMail, $sendResults, $queueStatus, $dateSent)) {
            echo "----------------------UPDATE EMAIL QUEUE COMPLETE------------------------- \n";
        } else {
            echo "----------------------UPDATE EMAIL QUEUE ERROR---------------------------- \n";
        }
        echo  $sendResults . "\n";
        echo "Email From " . $dataMail['from'] . "\n";
        echo "Email To " . $dataMail['to'] . "\n";
        echo "------------------------------------------------------- \n\n\n\n";

        return 'Completed';
    }

    /**
     * Get number of email sent today by email
     * @param strign $to
     * @return int
     */
    private function _getNumberOfEmailSentTodayByEmail($to) {
        $conditions = array(
            "conditions" => "date_sent = CONCAT(CURDATE(), '%') AND has_daily_send_limit = 1 AND to = ?1 AND status = ?2",
            "bind"       => array(
                1 => $to,
                2 => STATUS_SENT,
            ),
        );
        $emailQueues = EmailQueue::find($conditions);

        return $emailQueues->count();
    }

    /**
     * Update email queue result after sent
     * @param array $dataMail
     * @param string $queueResult
     * @param date $dateSent
     * @return bool
     */
    private function _updateEmailQueueResult($dataMail, $queueResult, $queueStatus, $dateSent = '') {
        $emailQueue = EmailQueue::findFirst($dataMail['id']);

        $emailQueue->from        = $dataMail['from'];
        $emailQueue->from_name   = $dataMail['from_name'];

        if ($dateSent != '') $emailQueue->date_sent = $dateSent;
        $emailQueue->sent_result = $queueResult;
        $emailQueue->status      = $queueStatus;

        return $emailQueue->save();
    }

    /**
     * Get attachments
     * @param int $emailQueueId
     * @return array
     */
    private function _getAttachments($emailQueueId) {
        return EmailQueueAttachment::find("queue_id = '$emailQueueId'");
    }

    /**
     * Get email configs
     * @return array configs
     */
    private function _setEmailAccount() {
        $provider                  = $this->_systemConfig['Sendmail-Provider'];
        $configs                   = $this->_providers[$provider];
        $emailProvider             = $configs['config'];
        $emailProvider['provider'] = $provider;

        $this->_emailAccount = $emailProvider;

        $accounts = json_decode($this->_systemConfig[$configs['account-key']], true);

        if (count($accounts) > 0) {
            /**
             * Get number of random from number of email we have
             * This number will using for get email configs random
             */
            $numberOfEmailRandom = rand(0, count($accounts) - 1);

            /**
             * Get defaul config email with email config type
             * @var array
             */
            $account = $accounts[$numberOfEmailRandom];
            $this->_emailAccount = array_merge($this->_emailAccount, $account);
        }
    }
}