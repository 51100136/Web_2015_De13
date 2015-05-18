<?php 
if (!function_exists('curl_init')){
	exit('Microsoft Class needs the CURL PHP extension');
}
if (!function_exists('json_decode')){
	exit('Microsoft Class needs the JSON DECODE extension');
}

// Define new Box Class
class BoxNet {
	private $clientID;
	private $clientSecret;
	private $scope;
	private $redirectUrl;

	// Constructor
	public function __construct($params){
		$this->clientID = (string) $params['client_id'];
		$this->clientSecret = (string) $params['client_secret'];
		$this->scope = (string) $params['client_scope'];
		$this->redirectUrl = (string) $params['redirect_url'];
	}

	################################ PUBLIC FUNCTION #################################
	// Return Redirect URL
	public function getRedirectUrl(){
		return $this->redirectUrl;
	}

	public function getLoginUrl(){
		$redirect_url = urlencode($this->redirectUrl);
		$scope = urlencode($this->scope);
		$clientid = urlencode($this->clientID);
		$dialog_url	= 'https://app.box.com/api/oauth2/authorize?client_id='.$clientid.'&scope='.$scope.'&response_type=code&redirect_uri='.$redirect_url;
		return $dialog_url;
	}
}

?>