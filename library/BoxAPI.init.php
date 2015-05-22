<?php
	session_start();

	if (!isset($_SESSION['username'])) {
		header("location: index.php");
		exit();
	}
	else $username = $_SESSION['username'];

	$client_id_box	= '6155u7ay8qv0euj1bo4hlsj06q9oizo3';
	$client_secret_box 	= 'wBnq9CGFtoZ6BaqJ9cmsbAHZDTK4wOug';
	$redirect_uri 	= 'http://localhost/Assignment/SkyBox_PHP/skybox.php';
	
	$box = new Box_API($client_id_box, $client_secret_box, $redirect_uri, $username);
	
	// Get token
	if(!$box->load_token()){
		if(isset($_GET['code'])){
			$token_box = $box->get_token($_GET['code'], true);
			if($box->write_token($token_box, 'file')){
				$box->load_token();
				header('Location: '. $redirect_uri);
			}
		}
	}
	

	// Get user details
	$user_info_box = $box->get_user();

	// Error
	if (isset($box->error)){
		echo $box->error . "\n";
	}

 ?>