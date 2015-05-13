<?php
class GoogleCloudMessaging{
	private $_apiKey;
	private $_vibrate;
	private $_sound;
	private $_url = 'https://android.googleapis.com/gcm/send';

	/**
	 * initial config
	 * @param array $config
	 */
	public function __construct($config){
		$this->_apiKey  = $config['apiKey'];
		$this->_vibrate = $config['vibrate'];
		$this->_sound   = $config['sound'];
	}

	/**
	 * Send notification
	 * @param  string $deviceToken 
	 * @param  array $message     array('title', 'message')
	 * @return array $result
	 */
	public function send($deviceToken, $message){
		if (!is_array($deviceToken)){
			$deviceToken = array($deviceToken);
		}
		$msg = array(
		    'message' 	=> $message['message'],
			'title'		=> $message['title'],
			'vibrate'	=> $this->_vibrate,
			'sound'		=> $this->_sound			
		);
	 
		$fields = array(
			'registration_ids' 	=> $deviceToken,
			'data'				=> $msg,
			// 'collapse_key'      => 'demo',
  	// 	    'delay_while_idle'  => true,
  	// 	    'time_to_live'      => 3
		);
	 
		$headers = array
		(
			'Authorization: key=' . $this->_apiKey,
			'Content-Type: application/json'
		);
	 	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, $this->_url);
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );	 
		$result = json_decode($result, true);
		return $result;
	}

}
?>