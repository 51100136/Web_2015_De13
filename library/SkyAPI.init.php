<?php 
	$client_id_sky	= '0000000040154D32';
	$client_secret_sky 	= '9SK1rJpxjAWsg1V38jGnD2o2HVzSCXgm';
	$redirect_uri 	= 'http://mytestingdomain.com/Assignment/SkyDrive_PHP/';

	// Create new class Sky
	$sky = new Sky_API($client_id_sky, $client_secret_sky, $redirect_uri);

	// Get login url
	$login_url_php_sky = $sky->get_login_url();

	// Get token
	if(!$sky->load_token()){
		if(isset($_GET['code'])){
			$token_sky = $sky->get_token($_GET['code'], true);
			if($sky->write_token($token_sky, 'file')){
				$sky->load_token();
				header('Location: '. $redirect_uri);
			}
		}
	}

	// Get user info
	$user_info_sky = $sky->get_user();
	if (!empty($user_info_sky['name'])) {
		$username_sky = $user_info_sky['name'];	
	}
	if (!empty($user_info_sky['id'])) {
		$sky->user_id = $user_info_sky['id'];
		// Get root folder ID
		$sky->root_folder_id = 'folder.' . $sky->user_id;
		$root_folder_id_sky = $sky->root_folder_id;
	}

?>