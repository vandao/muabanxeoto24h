<?php
include "../cli-bootstrap.php";

$push = new PushNotification($di);

/**
 * Push notification for all platforms
 */
class PushNotification
{
	private $_db;
    private $_config;
    private $_limit = 1000;
    private $_notificationData;

    public function __construct($di) {
    	$this->_db     = $di->getShared('dbNotification');
        $this->_config = $di->getShared('config')->notification->service;  
        $this->send();
    }

    /**
     * send request push notification to service provider
     *     get notification, limit 1000
     *     update notification is sending, if it is updated by another cron, ignore it
     *     add notification to the list
     *     send them
     */
    public function send(){
        while (1) {

            $notifications = NotificationQueue::find(array(
                "conditions" => "date_sent IS NULL AND status = ?1", 
                "bind" => array(1 => STATUS_PENDING,), 
                "order" => "message_id",
                "limit" => $this->_limit));           
            
            if (count($notifications) == 0) break;            
            
            /**
             * Update SENDING status for notification
             */
            foreach ($notifications as $notification) {
                // check there is another cron take this notification
                if ($this->_updateSendingStatus($notification)) {
                    $this->_addNotification($notification);
                }    
            }
            $this->_send();
            
        }
        echo '---------------No records---------------';
    }

    /**
     * Add notification and group by platform, message
     * @param NotificationQueue $notification
     */
    private function _addNotification($notification){        
        $this->_notificationData[$notification->platform][$notification->message_id][$notification->id] = $notification->device_token;        
    }

    /**
     * update status of notification to sending 
     * check there is another cron take it
     * @param  NotificationQueu $notification
     * @return boolean
     */
    private function _updateSendingStatus($notification){
    	try {
            $this->_db->begin();
            if ($notification->status == STATUS_PENDING) {
                $query  = "UPDATE notification_queue SET status = '" . STATUS_SENDING . "' WHERE id = '{$notification->id}'";
                $notification->status = STATUS_SENDING;
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
     * Handle sending push notification to GMC service
     * 
     * @param  array $notificationData notification data group by message
     * @return array $queueResults
     */
    private function _sendAndroid($notificationData){
        $queueResults = array('success' => 0, 'failure' => 0);

        foreach ($notificationData as $message_id => $notifications) {
            $message = NotificationMessage::findFirst($message_id);
            if ($message){
                $message = $message->toArray();
                $deviceTokens = array_values($notifications);                

                include_once('platform/android.php');
                $gcm = new GoogleCloudMessaging($this->_config->gcm);
                $results = $gcm->send($deviceTokens, $message);
                
                $queueResults['success'] += $results['success'];
                $queueResults['failure'] += $results['failure'];

                $notificationIds = array_keys($notifications);                
                foreach ($notificationIds as $index => $id) {
                    $notification = NotificationQueue::findFirst($id);
                    $sent_result = $results['results'][$index];

                    if (isset($sent_result['error'])){                        
                        $notification->status      = STATUS_FAILED;
                        $notification->sent_result = $sent_result['error'];
                    }

                    if (isset($sent_result['message_id'])){                        
                        $notification->status      = STATUS_SENT;
                        $notification->sent_result = $sent_result['message_id'];
                        $notification->date_sent   = date('Y-m-d H:i:s');
                    }

                    $notification->save();
                }                
            }
        }
        return $queueResults;
    }

    /**
     * Handle sending push notification to APNS service     
     * 
     * @param  NotificationQueue $notificationData
     * @return 
     */
    private function _sendIos($notificationData){
        $queueResults = array('success' => 0, 'failure' => 0);

        foreach ($notificationData as $message_id => $notifications) {
            $message = NotificationMessage::findFirst($message_id);
            if ($message){                
                $deviceTokens = array_values($notifications);
                $notificationIds = array_keys($notifications);
                
                include_once('platform/ios.php');
                $apns = new APNS($this->_config->apns);
                $results = $apns->send($deviceTokens, $message->message);                

                $queueResults['success'] += $results['success'];
                $queueResults['failure'] += $results['failure'];
                               
                foreach ($notificationIds as $index => $id) {
                    $notification = NotificationQueue::findFirst($id);
                    $sent_result = $results['results'][$index];

                    if ($sent_result == 'success'){                        
                        $notification->status      = STATUS_SENT;
                        $notification->sent_result = STATUS_SENT;
                        $notification->date_sent   = date('Y-m-d H:i:s');
                    }else{
                        $notification->status      = STATUS_FAILED;
                        $notification->sent_result = STATUS_ERROR;
                    }

                    $notification->save();
                }                
            }
        }
        return $queueResults;
    }

    /**
     * Handle sending push notification 
     * 
     */
    private function _send(){
        foreach ($this->_notificationData as $platform => $notificationData) {
            switch ($platform){
                case "android": $queueResults = $this->_sendAndroid($notificationData);
                                echo "---------------Push notification of $platform completed -------------------\n";
                                echo "Success: " . $queueResults['success'] . "\n";
                                echo "Failure: " . $queueResults['failure'] . "\n";
                                break;
                case "ios"    : $queueResults = $this->_sendIos($notificationData);
                                echo "---------------Push notification of $platform completed -------------------\n";
                                echo "Success: " . $queueResults['success'] . "\n";
                                echo "Failure: " . $queueResults['failure'] . "\n";
                                break;

            }
        }

    }
}
