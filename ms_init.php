<?php 
######################## SKYDRIVE SETTINGS ###############################
$client_id	= '0000000040154D32';
$client_secret 	= 'wBnq9CGFtoZ6BaqJ9cmsbAHZDTK4wOug';
$redirect_uri 	= 'http://localhost/Assignment/Box_PHP/';

// Create new class
$ms_new = new MicrosoftLive(array(
	'client_id' => $ms_client_id,
	'client_secret' => $ms_client_secret,
	'client_scope' => $ms_permission,
	'redirect_url' => $ms_redirect_url
));

// Get UserInfo
	$user_info = $ms_new->getUser();

// Get and set access token from microsoft
if (!$user_info && isset($_GET["code"])){
	$access_token = $ms_new->getAccessToken($_GET["code"]);
	$ms_new->setAccessToken($access_token);
	header('Location: '.$ms_new->getRedirectUrl());
}

// User log out
if(isset($_GET["logout"])){
	$ms_new->distroySession();
	header('Location: '.$ms_new->getRedirectUrl());
}


?>