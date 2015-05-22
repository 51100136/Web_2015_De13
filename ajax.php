<?php 
	session_start();
	if (!isset($_SESSION['username'])) {
		header("location: index.php");
		exit();
	}
	else $username = $_SESSION['username'];

	include('library/BoxAPI.class.php');
	include("library/SkyAPI.class.php");

	$client_id_box	= '6155u7ay8qv0euj1bo4hlsj06q9oizo3';
	$client_secret_box 	= 'wBnq9CGFtoZ6BaqJ9cmsbAHZDTK4wOug';
	$client_id_sky	= '0000000040154D32';
	$client_secret_sky 	= '9SK1rJpxjAWsg1V38jGnD2o2HVzSCXgm';
	$redirect_uri 	= 'http://localhost/Assignment/SkyBox_PHP/skybox.php';

	$box = new Box_API($client_id_box, $client_secret_box, $redirect_uri, $username);
	$sky = new Sky_API($client_id_sky, $client_secret_sky, $redirect_uri, $username);

	$token_box = $box->load_token();
	$token_sky = $sky->load_token();

	if (isset($_POST['logout_user'])) {
		session_destroy();
		echo "Successful";
		$box->delete_token();
		$sky->delete_token();
	}

	################################################### AJAX BOX ########################################################
	// AJAX LOGOUT
	if ($token_box && isset($_POST['logout_box'])) {
		$result = $box->getLogoutUrl();
		if ($result) echo "Successful log user out!";
		else echo "Failed log user out";
	}

	//AJAX CREATE FOLDER
	if ($token_box && isset($_POST['create_folder_box'])) {
		$folderName = $_POST['folder_name_box'];
		$id = $_POST['id_box'];
		$result = $box->create_folder($folderName, $id);
		if (empty($result->error)) {
			echo "Successful create folder: ";
			echo "$result";
		}
		else {
			echo "Failed to create folder";
		}
	}

	//AJAX UPLOAD
	if($token_box && isset($_POST['upload_box'])) {
		$filename = $_POST['file_box'];
		$folderid = $_POST['id_box'];
		$result = $box->upload_file($filename, $folderid);
		if (!empty($result['code'])) {
			echo "File name is exist!";
		}
		else if (!empty($result['entries'][0]['name'])) {
			echo "Successful create file: ";
			echo $result['entries'][0]['name'];
		}
	}

	//AJAX DOWNLOAD
	if($token_box && isset($_POST['download_box'])) {
		$fileid = $_POST['id_box'];
		$result = $box->download_file($fileid);

		if ($result == false) echo "-1";
		else echo "$result";
	}

	//AJAX DELETE FOLDER
	if($token_box && isset($_POST['del_folder_box'])) {
		$folderid = $_POST['id_box'];
		$opts['recursive'] = 'true';
		$result = $box->delete_folder($folderid, $opts);

		echo "$result";
	}

	//AJAX DELETE FILE
	if($token_box && isset($_POST['del_file_box'])) {
		$fileid = $_POST['id_box'];
		$result = $box->delete_file($fileid);

		echo "$result";
	}

	//AJAX LOAD PAGE
	if($token_box && isset($_POST['load_box'])) {
		$folderid = $_POST['id_box'];
		$folder_items = $box->get_folder_items($folderid);

		if ($folder_items['total_count'] >= 0) {
			echo "
			<input type='hidden' name='cur_folder_id_box' value=\"$folderid\" id='cur_folder_id_box'>
			<table class='table table-striped table-bordered' cellspacing='0' width='100%'>
				<thead>
					<tr>
						<th class='col-md-1'>Type</th>
						<th class='col-md-4'>Name</th>
						<th class='col-md-7'>Function</th>
					</tr>
				</thead>
				<!-- Body -->
				<tbody>
				";
				foreach ($folder_items['entries'] as $key) {
					if ($key['type'] == 'folder') {
						$name = $key['name'];
						$id = $key['id'];
						$folder_details = $box->get_folder_details($id);
						$link = $folder_details['shared_link']['url'];
						$fname = $folder_details['name'];
						$description = $folder_details['description'];
						$owner = $folder_details['owned_by']['name'];
						$day = $folder_details['created_at'];
						$size = $folder_details['size'] . ' Bytes';
						if ($link == NULL) $link = "Folder is not shared";

						if ($description == NULL) $description = "No description";

						// Get parent folder
						echo "
						<tr>
							<td class='col-md-1'>Folder</td>
							<td class='col-md-4'>$name</td>
							<td class='col-md-7'>
								<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$fname\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
								<input type='button' class='folder_delete_put_box btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#folder_delete_modal_box' data-id=\"$id\">
								<input type='button' class='btn btn-xs btn-success' value='Go to....' onclick=\"ajax_load_box('$id')\">
								<input type='button' class='get_link_put_box btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_box' data-id=\"$link\">
							</td>
						</tr>	
						";
					}
					else if ($key['type'] == 'file') {
						$name = $key['name'];
						$id = $key['id'];
						$file_details = $box->get_file_details($id);
						$link = $file_details['shared_link']['url'];
						$fname = $file_details['name'];
						$description = $file_details['description'];
						$owner = $file_details['owned_by']['name'];
						$day = $file_details['created_at'];
						$size = $file_details['size'] . ' Bytes';
						if ($link == NULL) $link = "File is not shared";

						if ($description == NULL) $description = "No description";

						echo "
						<tr>
							<td class='col-md-1'>File</td>
							<td class='col-md-4'>$name</td>
							<td class='col-md-7'>
								<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$fname\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
								<input type='button' class='file_delete_put_box btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#file_delete_modal_box' data-id=\"$id\">
								<input type='button' class='btn btn-xs btn-warning' value='DOWNLOAD' onclick=\"call_download_box('$id')\">
								<input type='button' class='get_link_put_box btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_box' data-id=\"$link\">
							</td>
						</tr>
						";							
					}
				}
			echo"
				</tbody>
			</table>";
		}	
	}

################################################## AJAX SKYDRIVE ###########################################################
	// LOG OUT
	if (isset($_POST['logout_sky'])) {
		$result = $sky->get_logout_url();
		if ($result) echo "Successful log user out!";
		else echo "Failed log user out";
	}

	// CREATE FOLDER
	if ($token_sky && isset($_POST['create_folder_sky'])) {
		$folder_name = $_POST['folder_name_sky'];
		$folder_id = $_POST['id_sky'];
		$result = $sky->create_folder($folder_name, $folder_id);

		if (empty($result['error'])) {
			echo "Successful create folder: ";
			echo $result['name'];
		}
		else echo "Failed to create folder";
	}

	// DELETE FOLDER
	if ($token_sky && isset($_POST['delete_folder_sky'])) {
		$folder_id = $_POST['id_sky'];
		$result = $sky->delete_folder($folder_id);

		if (isset($result['error'])) {
			if ($result['error']['code'] = 'resource_not_found') echo "Folder doesn't exist!";
			else echo "Unknown Error";
		}
		else echo "Successful!";
	}

	// DELETE FILE
	if ($token_sky && isset($_POST['delete_file_sky'])) {
		$file_id = $_POST['id_sky'];
		$result = $sky->delete_file($file_id);

		if (isset($result['error'])) {
			if ($result['error']['code'] = 'resource_not_found') echo "Folder doesn't exist!";
			else echo "Unknown Error";
		}
		else echo "Successful!";
	}

	// UPLOAD FILE
	if($token_sky && isset($_POST['upload_sky'])) {
		$filename = $_POST['file_sky'];
		$folderid = $_POST['id_sky'];
		$result = $sky->upload_file($folderid, $filename);
		if (!empty($result['error']) || !empty($result['code']) || empty($result)) {
			echo "Error upload file";
		}
		else if (!empty($result['name'])) {
			echo "Successful upload file: ";
			echo $result['name'];
		}
	}

	if($token_sky && isset($_POST['load_sky'])) {
		$folderid = $_POST['id_sky'];
		$folder_items = $sky->get_folder_items($folderid);
		$count_items = count($folder_items['data']);
		if ($count_items >= 0) {
			echo "
			<input type='hidden' name='cur_folder_id_sky' value=\"$folderid\" id='cur_folder_id_sky'>
			<table class='table table-striped table-bordered' cellspacing='0' width='100%'>
				<thead>
					<tr>
						<th class='col-md-1'>Type</th>
						<th class='col-md-4'>Name</th>						
						<th class='col-md-7'>Function</th>
					</tr>
				</thead>
				<!-- Body -->
				<tbody>
				";
			foreach ($folder_items['data'] as $key) {
				if ($key['type'] == 'folder') {
					$name = $key['name'];
					$id = $key['id'];
					$link = $key['link'];
					$description = $key['description'];
					$owner = $key['from']['name'];
					$day = $key['created_time'];
					$size = $key['size'] . ' Bytes';

					if ($description == NULL) $description = "No description";

					// Get parent folder
					echo "
					<tr>
						<td class='col-md-1'>Folder</td>
						<td class='col-md-4'>$name</td>
						<td class='col-md-7'>
							<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$name\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
							<input type='button' class='folder_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#folder_delete_modal_sky' data-id=\"$id\">
							<input type='button' class='btn btn-xs btn-success' value='Go to....' onclick=\"ajax_load_sky('$id')\">
							<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
						</td>
					</tr>	
					";
				}
				else if ($key['type'] == 'album') {
					$name = $key['name'];
					$id = $key['id'];
					$link = $key['link'];
					$description = $key['description'];
					$owner = $key['from']['name'];
					$day = $key['created_time'];
					$size = $key['size'] . ' Bytes';

					if ($description == NULL) $description = "No description";

					// Get parent folder
					echo "
					<tr>
						<td class='col-md-1'>Folder</td>
						<td class='col-md-4'>$name</td>							
						<td class='col-md-7'>
							<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$name\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
							<input type='button' class='folder_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#folder_delete_modal_sky' data-id=\"$id\">
							<input type='button' class='btn btn-xs btn-success' value='Go to....' onclick=\"ajax_load_sky('$id')\">
							<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
						</td>
					</tr>	
					";
				}
				else if ($key['type'] == 'file') {
					$name = $key['name'];
					$id = $key['id'];
					$link = $key['link'];
					$source = $key['source'];
					$description = $key['description'];
					$owner = $key['from']['name'];
					$day = $key['created_time'];
					$size = $key['size'] . ' Bytes';

					if ($description == "") $description = "No discription";

					echo "
					<tr>
						<td class='col-md-1'>File</td>
						<td class='col-md-4'>$name</td>						
						<td class='col-md-7'>
							<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$name\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
							<input type='button' class='file_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#file_delete_modal_sky' data-id=\"$id\">
							<input type='button' class='btn btn-xs btn-warning' value='DOWNLOAD' onclick=\"call_download_sky('$source')\">
							<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
						</td>
					</tr>
					";							
				}
				else if ($key['type'] == 'photo') {
					$name = $key['name'];
					$id = $key['id'];
					$link = $key['link'];
					$source = $key['source'];
					$description = $key['description'];
					$owner = $key['from']['name'];
					$day = $key['created_time'];
					$size = $key['size'] . ' Bytes';

					if ($description == "") $description = "No discription";

					echo "
					<tr>
						<td class='col-md-1'>File</td>
						<td class='col-md-4'>$name</td>						
						<td class='col-md-7'>
							<input type='button' class='folder_info_put_box btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folder_info_modal_box' data-name=\"$name\" data-description=\"$description\" data-owner=\"$owner\" data-day=\"$day\" data-size=\"$size\">
							<input type='button' class='file_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#file_delete_modal_sky' data-id=\"$id\">
							<input type='button' class='btn btn-xs btn-warning' value='DOWNLOAD' onclick=\"call_download_sky('$source')\">
							<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
						</td>
					</tr>
					";							
				}
			}
			echo"
				</tbody>
			</table>";
		}
	}
 ?>