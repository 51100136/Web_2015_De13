<?php 
// Check if function curl_init() and json_decode is exists or not
if (!function_exists('curl_init')){
	exit('Microsoft Class needs the CURL PHP extension');
}
if (!function_exists('json_decode')){
	exit('Microsoft Class needs the JSON DECODE extension');
}

// Define new class
class MicrosoftLive {
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

	// Return login url
	public function getLoginUrl(){
		$redirect_url = urlencode($this->redirectUrl);
		$scope = urlencode($this->scope);
		$clientid = urlencode($this->clientID);
		$dialog_url	= 'https://login.live.com/oauth20_authorize.srf?client_id='.$clientid.'&scope='.$scope.'&response_type=code&redirect_uri='.$redirect_url;
		return $dialog_url;
	}

	// Return logout url
	public function getLogoutUrl(){
		$dialog_url = $this->redirectUrl;
		$dialog_url = rtrim($dialog_url, '/');
		$dialog_url = $dialog_url . '?logout=1';
		return $dialog_url;
	}

	// Get access token
	public function getAccessToken($code=null){
		$token = $this->getSessionVar('ms_access_token');

		if ($token && !$code){
			return $token;
		}
		else{
			$url = "https://login.live.com/oauth20_token.srf";
			$field = array(
				'client_id' => urlencode($this->clientID),
				'redirect_uri' => urlencode($this->redirectUrl),
				'client_secret' => urlencode($this->clientSecret),
				'code' => urlencode($code),
				'grant_type' => urlencode('authorization_code')
				);
			$result = $this->HttpPost($url, 1, $field);

			if (!$result){
				return false;
			}

			$authCode = json_decode($result);
			return $authCode->access_token;
		}
	}

	// Set access token
	public function setAccessToken($token){
		$this->setSessionVar('ms_access_token', $token);
	}

	// Destroy session
	public function distroySession(){
		$this->initiateSession();
		session_destroy();
	}

	// Get userInfo
	public function getUser(){
		$getAccessToken = $this->getAccessToken();
		$url = "https://apis.live.net/v5.0/me?access_token=".$getAccessToken;
		$result = json_decode($this->HttpPost($url));

		if (!empty($result->error)){
			return false;
		}
		else{
			return $result;
		}
	}

	############################### PRIVATE FUNCTION #################################
	// HTTPPOST
	private function HttpPost($url=null, $post=0, $postargs=array()){
		$fields_string = "";
		$header = array("Content-Type: application/x-www-form-urlencoded");
		$ch = curl_init();
		// Set POST URL
		//curl_setopt($ch, CURLOPT_URL, $url);
		if($post){
			foreach($postargs as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = trim($fields_string, '&');
			//curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		}
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		//curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    //	'Content-Type: application/x-www-form-urlencoded'                                                                                                                                   
		//));	
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_POSTFIELDS => $fields_string,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_POST => $post
			));
		$result = curl_exec($ch);
		if (!$result){
			echo "Cant get data";
		}
		curl_close($ch);
		return $result;
	}

	// Set session variable
	private function setSessionVar($key, $value){
		$this->initiateSession();
		$_SESSION[$key] = $value;
	}

	// Get session variable
	private function getSessionVar($key){
		$this->initiateSession();
		if(isset($_SESSION[$key])){
			return $_SESSION[$key];
		}
	}

	// Start session
	private function initiateSession(){
		if (!session_id()){
			session_start();
		}
	}
}

?>