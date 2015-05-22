<?php 
	class Sky_API {

		public $client_id 		= '';
		public $client_secret 	= '';
		public $redirect_uri	= '';
		public $access_token	= '';
		public $refresh_token	= '';
		public $filename		= '';
		public $scope			= 'wl.signin onedrive.readwrite onedrive.appfolder wl.skydrive ,wl.skydrive_update';
		public $authorize_url 	= 'https://login.live.com/oauth20_authorize.srf';
		public $token_url	 	= 'https://login.live.com/oauth20_token.srf';
		public $api_url 		= 'https://apis.live.net/v5.0';
		public $logout_url 		= 'https://login.live.com/oauth20_logout.srf';
		public $user_url		= 'https://apis.live.net/v5.0/me';
		public $root_folder_id	= '';
		public $user_id			= '';
		public function __construct($client_id = '', $client_secret = '', $redirect_uri = '', $username ='') {
			if(empty($client_id) || empty($client_secret)) {
				throw ('Invalid CLIENT_ID or CLIENT_SECRET or REDIRECT_URL. Please provide CLIENT_ID, CLIENT_SECRET and REDIRECT_URL when creating an instance of the class.');
			} else {
				$this->client_id 		= $client_id;
				$this->client_secret	= $client_secret;
				$this->redirect_uri		= $redirect_uri;
				$this->filename 		= 'token/' . $username . '.sky';
			}
		}

		#####################################  PUBLIC FUNTION ########################################
		public function get_redirect_uri() {
			return $this->redirect_uri;
		}

		public function get_login_url() {
			$url = $this->authorize_url . '?' . http_build_query(array('client_id' => $this->client_id, 'scope' => $this->scope, 'response_type' => 'code', 'redirect_uri' => $this->redirect_uri));
			return $url;
		}

		public function get_token($code = '', $json = false) {
			$url = $this->token_url;
			if(!empty($this->refresh_token)){
				$params = array(
				'client_id' => urlencode($this->client_id),
				'redirect_uri' => urlencode($this->redirect_uri),
				'client_secret' => urlencode($this->client_secret),
				'refresh_token' => urlencode($this->refresh_token),
				'grant_type' => urlencode('refresh_token')
				);
			} else {
				$params = array(
				'client_id' => urlencode($this->client_id),
				'redirect_uri' => urlencode($this->redirect_uri),
				'client_secret' => urlencode($this->client_secret),
				'code' => urlencode($code),
				'grant_type' => urlencode('authorization_code')
				);
			}
			//
			$fields_string = "";
			foreach($params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = trim($fields_string, '&');
			
			//
			$finalurl = $url . '?' . $fields_string;
			$result = $this->get($finalurl);
			if($json){
				return $result;
			} else {
				$data = json_decode($result, true);
				return $data;
			}
		}

		public function get_logout_url() {
			$url = $this->logout_url;

			$params = array(
				'client_id' => urlencode($this->client_id),
				'redirect_uri' => urlencode($this->redirect_uri)
			);
			//
			$fields_string = "";
			foreach($params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = trim($fields_string, '&');
			$finalurl = $url . '?' . $fields_string;
			$result = $this->get($finalurl);
			//
			if (!empty($result->error)) {
				return false;
			}
			else {
				$this->delete_token();
				return true;
			}
		}

		public function get_user($json = false) {
			$url = $this->build_url($this->user_url, "");
			$result = $this->get($url);
			if($json){
				return $result;
			} else {
				$data = json_decode($result, true);
				return $data;
			}
		}

		public function create_folder($name, $parent_id) {
			$url = $this->build_url($this->api_url, "/$parent_id");
			$params = array('name' => $name);
			$headers = array(
				'Content-Type: application/json'
				);
			$result = $this->post_head($url, json_encode($params), $headers, true);
			$data = json_decode($result, true);
			return $data;
		}

		public function delete_folder($folder_id) {
			$url = $this->build_url($this->api_url, "/$folder_id");
			$result = $this->delete($url);
			$data = json_decode($result, true);
			return $data;
		}

		public function get_folder_items($folder_id) {
			$url = $this->build_url($this->api_url, "/$folder_id/files");
			$result = $this->get($url);
			$data = json_decode($result, true);	
			return $data;
		}

		public function download_file($file_id) {
			$url = $this->build_url($this->api_url, "/$file_id/content?suppress_redirects=true");
			$result = $this->get($url);
			$data = json_decode($result, true);
			return $data;
		}

		public function delete_file($file_id) {
			$url = $this->build_url($this->api_url, "/$file_id");
			$result = $this->delete($url);
			$data = json_decode($result, true);
			return $data;
		}

		public function upload_file($folder_id, $file_path) {
			$file_name = basename($file_path);
			$final_name = urlencode($file_name);
			$url = $this->build_url($this->api_url, "/$folder_id/files/$final_name");
			$result = $this->put_file($url, $file_path);
			$data = json_decode($result, true);
			
			return $data;
		}

		

		####################################### TOKEN HANDLE #########################################
		public function write_token($token, $type = 'file') {
			$array = json_decode($token, true);
			if(isset($array['error'])){
				$this->error = $array['error_description'];
				return false;
			} else {
				$array['timestamp'] = time();
				if($type == 'file'){
					$fp = fopen("$this->filename", 'w');
					fwrite($fp, json_encode($array));
					fclose($fp);
				}
				return true;
			}
		}
		
		/* Reads the token */
		public function read_token($type = 'file', $json = false) {
			if($type == 'file' && file_exists("$this->filename")){
				$fp = fopen("$this->filename", 'r');
				$content = fread($fp, filesize("$this->filename"));
				fclose($fp);
			} else {
				return false;
			}
			if($json){
				return $content;
			} else {
				return json_decode($content, true);
			}
		}
		
		/* Loads the token */
		public function load_token() {
			$array = $this->read_token('file');
			if(!$array){
				return false;
			} else {
				if(isset($array['error'])){
					$this->error = $array['error_description'];
					return false;
				} else if($this->expired($array['expires_in'], $array['timestamp'])){
					$this->refresh_token = $array['refresh_token'];
					$token = $this->get_token(NULL, true);
					if($this->write_token($token, 'file')){
						$array = json_decode($token, true);
						$this->refresh_token = $array['refresh_token'];
						$this->access_token = $array['access_token'];
						return true;
					}
				} else {
					$this->refresh_token = $array['refresh_token'];
					$this->access_token = $array['access_token'];
					return true;
				}
			}
		}

		/* Delete token when log out */
		public function delete_token() {
			if (file_exists("$this->filename")) {
				unlink("$this->filename");
			}
		}

		##################################### PRIVATE FUNCTION #######################################
		/* Builds the URL for the call */
		private function build_url($url, $api_func, array $opts = array()) {
			$opts = $this->set_opts($opts);
			$base = $url . $api_func . '?';
			$query_string = $opts;
			$base = $base . $query_string;
			return $base;
		}
		
		/* Sets the required before building the query */
		private function set_opts(array $opts) {
			if(!array_key_exists('access_token', $opts)) {
				$opts['access_token'] = $this->access_token;
			}
			$fields_string = "";
			foreach($opts as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			$fields_string = trim($fields_string, '&');
			return $fields_string;
		}
		
		private function parse_result($res) {
			$xml = simplexml_load_string($res);
			$json = json_encode($xml);
			$array = json_decode($json,TRUE);
			return $array;
		}
		
		private static function expired($expires_in, $timestamp) {
			$ctimestamp = time();
			if(($ctimestamp - $timestamp) >= $expires_in){
				return true;
			} else {
				return false;
			}
		}
		
		private static function get($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		private static function get_header($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_HEADER, true);
			$data = curl_exec($ch);
			$header = curl_getinfo($ch);
			curl_close($ch);
			return $header;
		}
		
		private static function post($url, $params) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		private static function post_head($url, $params=array(), $headers, $json = false) {
			$ch = curl_init();
			if (!$json) {
				$fields_string = "";
				foreach($params as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
				$fields_string = trim($fields_string, '&');
			}	
			/*curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_POSTFIELDS => $fields_string,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true
			));*/
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		private static function put($url, array $params = array()) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}
		
		private static function delete($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		private static function put_file($uri, $fp) {
		  	$result = "";
		  	$pointer = fopen($fp, 'r+');
		  	$stat = fstat($pointer);
		  	$pointersize = $stat['size'];
			$ch = curl_init($uri);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_PUT, true);
			curl_setopt($ch, CURLOPT_INFILE, $pointer);
			curl_setopt($ch, CURLOPT_INFILESIZE, (int)$pointersize);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_FRESH_CONNECT, TRUE);	
			
			$result = curl_exec($ch);
			curl_close($ch);
			
			return $result;
		}

	}
?>