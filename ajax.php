<?php 
	include_once("library/SkyAPI.class.php");

	$client_id_sky	= '0000000040154D32';
	$client_secret_sky 	= '9SK1rJpxjAWsg1V38jGnD2o2HVzSCXgm';
	$redirect_uri 	= 'http://mytestingdomain.com/Assignment/SkyDrive_PHP/';

	// Create new class Sky
	$sky = new Sky_API($client_id_sky, $client_secret_sky, $redirect_uri);

	$token_sky = $sky->load_token();

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

	if($token_sky && isset($_POST['load_sky'])) {
		$folderid = $_POST['id_sky'];
		$folder_items = $sky->get_folder_items($folderid);
		$count_items = count($folder_items['data']);
		if ($count_items > 0) {
			echo "
			<input type='hidden' name='cur_folder_id_sky' value=\"$folderid\" id='cur_folder_id_sky'>
			<table class='table table-striped table-bordered' cellspacing='0' width='100%'>
				<thead>
					<tr>
						<th class='col-md-1'>Type</th>
						<th class='col-md-2'>Folder Type</th>
						<th class='col-md-4'>Name</th>						
						<th class='col-md-5'>Function</th>
					</tr>
				</thead>
				<!-- Body -->
				<tbody>
				";
			if ($count_items > 0) {
				foreach ($folder_items['data'] as $key) {
					if ($key['type'] == 'folder') {
						$name = $key['name'];
						$id = $key['id'];
						$link = $key['link'];
						// Get parent folder
						echo "
						<tr>
							<td class='col-md-1'>Folder</td>
							<td class='col-md-2'>Document</td>
							<td class='col-md-4'>$name</td>
							<td class='col-md-5'>
								<input type='button' class='btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folderModal'>
								<input type='button' class='folder_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#folder_delete_modal_sky' data-id=\"$id\">
								<input type='button' class='btn btn-xs btn-success' value='Go to....' onclick=\"ajax_load_sky('$id')\">
								<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared Link' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
							</td>
						</tr>	
						";
					}
					else if ($key['type'] == 'album') {
						$name = $key['name'];
						$id = $key['id'];
						$link = $key['link'];
						// Get parent folder
						echo "
						<tr>
							<td class='col-md-1'>Folder</td>
							<td class='col-md-2'>Album</td>
							<td class='col-md-4'>$name</td>							
							<td class='col-md-5'>
								<input type='button' class='btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#folderModal'>
								<input type='button' class='folder_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#folder_delete_modal_sky' data-id=\"$id\">
								<input type='button' class='btn btn-xs btn-success' value='Go to....' onclick=\"ajax_load_sky('$id')\">
								<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared Link' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
							</td>
						</tr>	
						";
					}
					else if ($key['type'] == 'file') {
						$name = $key['name'];
						$id = $key['id'];
						$link = $key['link'];
						$source = $key['source'];
						echo "
						<tr>
							<td class='col-md-1'>File</td>
							<td class='col-md-2'></td>
							<td class='col-md-4'>$name</td>						
							<td class='col-md-5'>
								<input type='button' class='btn btn-xs btn-info' value='DETAIL' data-toggle='modal' data-target='#fileModal'>
								<input type='button' class='file_delete_put_sky btn btn-xs btn-danger' value='DELETE' data-toggle='modal' data-target='#file_delete_modal_sky' data-id=\"$id\">
								<input type='button' class='btn btn-xs btn-warning' value='DOWNLOAD' onclick=\"call_download_sky('$source')\">
								<input type='button' class='btn btn-xs btn-primary' value='UPDATE'>
								<input type='button' class='get_link_put_sky btn btn-xs btn-link' value='Shared Link' data-toggle='modal' data-target='#get_link_modal_sky' data-id=\"$link\">
							</td>
						</tr>
						";							
					}
				}
			}
			echo"
				</tbody>
			</table>";
		}
	}
 ?>