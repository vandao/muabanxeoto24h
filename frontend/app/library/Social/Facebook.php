<?php
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class Facebook extends \Phalcon\Mvc\User\Component {
	private $_appId;
	private $_secret;
	private $_scope 	  	  = 'email,offline_access,user_birthday,public_profile,publish_actions,publish_stream,user_friends';
	private $_redirectUrl 	  = '';

	private $_helper;
	private $_session;

	private $_responseStatus  = 'error';
	private $_responseMessage = '';
	private $_responseData    = array();

	public function __construct($appId, $secret, $scope = '', $redirectUrl = '', $oldToken = '') {
		$this->_redirectUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    	if ($appId != '')  		 $this->_appId 		 = $appId;
    	if ($secret != '') 		 $this->_secret 	 = $secret;

    	if ($scope != '')  		 $this->_scope 		 = $scope;
    	if ($redirectUrl != '')  $this->_redirectUrl = $redirectUrl;

    	if ($this->_appId == '') {
    		$this->_responseMessage = 'Missing app ID';
    	} elseif ($this->_secret == '') {
    		$this->_responseMessage = 'Missing secret';
    	}

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
	}

    public function authenticate() {
		FacebookSession::setDefaultApplication($this->_appId, $this->_secret);

		$this->_helper  = new FacebookRedirectLoginHelper($this->_redirectUrl);
		$this->_session = $this->_helper->getSessionFromRedirect();

		if (! $this->_session) {
		  	$this->_responseData['login_url'] = $this->_helper->getLoginUrl(array('scope' => $this->_scope));
		} else {
		    $this->_responseStatus = 'success';
		}

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
    }

    public function getUser() {
	    try {
		    $userProfile = (new FacebookRequest($this->_session, 'GET', '/me'))
		    	->execute()
		    	->getGraphObject(GraphUser::className());

		    if ($userProfile) {
		    	$userProfile         = $userProfile->asArray();

		    	$this->_responseData = array(
		    		'email'          => $userProfile['email'],
		    		'first_name'     => $userProfile['first_name'],
		    		'last_name'      => $userProfile['last_name'],
		    		'gender'         => ucwords($userProfile['gender']),
		    		'identity'       => $userProfile['id'],
		    		'token'          => $this->_session->getToken(),
		    		'date_of_birth'  => date('Y-m-d', strtotime($userProfile['birthday'])),
		    		'avatar'		 => 'https://graph.facebook.com/'. $userProfile['id'] . '/picture',
		    	);
			}
	  	} catch(FacebookRequestException $e) {
	    	$this->_responseMessage  = "Exception occured, code: " . $e->getCode();
	    	$this->_responseMessage .= " with message: " . $e->getMessage();
	  	}

        return AjaxResponse::toArray($this->_responseStatus, $this->_responseMessage, $this->_responseData);
    }
}