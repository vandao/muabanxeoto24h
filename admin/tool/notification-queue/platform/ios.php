<?php 
class APNS{
	// Put your private key's passphrase here:
	private $_passphrase;
	// Put your pem file path here 'ck_file_name.pem'
	private $_pemFile;
	private $_button;
	private $_sound;

	public function __construct($config){
		// Put your private key's passphrase here:
		$this->_passphrase = $config['passphrase'];
		$this->_pemFile    = $config['pemFile'];
		$this->_button     = $config['button'];
		$this->_sound      = $config['sound'];
		$this->_badge      = $config['badge'];
	}

	public function send($deviceTokens, $message){
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $this->_pemFile);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $this->_passphrase);
		 
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', 
			$err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		 
		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);
		 
		echo 'Connected to APNS' . PHP_EOL;
		 
		// Create the payload body
		$body['aps'] = array(
			'alert' => array(
		        'body' => $message,
				'action-loc-key' => $this->_button,
		    ),
		    'badge' => $this->_badge,
			'sound' => $this->_sound,
			);
		 
		// Encode the payload as JSON
		$payload = json_encode($body);
		
		if (!is_array($deviceTokens)){
			$deviceTokens = array($deviceTokens);
		}

		$sentResults = array(
			'success' => 0,
			'failure' => 0,
			'results' => array()
		);
		foreach ($deviceTokens as $deviceToken) {
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			 
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));			 

			if (!$result){
				$sentResults['failure']++;
				$sentResults['results'][] = 'error';
			}else{
				$sentResults['success']++;
				$sentResults['results'][] = 'success';
			}
		}		
		 
		// Close the connection to the server
		fclose($fp);
		return $sentResults;
	}
}
?>