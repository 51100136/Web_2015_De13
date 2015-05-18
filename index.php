<?php 
	// Include ms_skydrive.php file
	include_once("library/SkyAPI.class.php");
	include_once("library/SkyAPI.init.php");
?>

<!DOCTYPE html>
<html>
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<!-- Title -->
	<title>SkyDrive API</title>
	<!-- Link & Script -->
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<style type="text/css"> 
     @font-face {   font-family: 'Glyphicons Halflings';   
	     src: url('fonts/glyphicons-halflings-regular.eot');   
	     src: url('fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'), 
	     url('fonts/glyphicons-halflings-regular.woff') format('woff'),  
	     url('fonts/glyphicons-halflings-regular.ttf') format('truetype'), 
	     url('fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg'); } 
	</style>
    <script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/SkyAPI.init.js"></script>
</head>
<body>
	<div class="container">
		<?php
		// If have UserInfo
		if(!empty($sky->access_token)) {
			echo "<input type='hidden' name='root_folder_id_sky' value=\"$root_folder_id_sky\" id='root_folder_id_sky'>";
			echo "
				<h3>Welcome back</h3>
				<br>
				$username_sky
			";
			echo "
				<input type='button' class='btn btn-xs btn-danger' value='LOG OUT' onclick=\"ajax_logout('$redirect_uri')\">
			";
			echo "<br>";
			echo "<br>";
			echo "
				<input type='button' class='btn btn-xs btn-info' value='New Folder' data-toggle='modal' data-target='#create_folder_modal_sky'>
			";
			echo "<br>";
			echo "<br>";
			echo "
				<input type='file' name='upload' id='upload_sky' onchange='getFileName(this)'>
			";

			echo "
				<input type='button' class='btn btn-xs btn-info' value='Upload' onclick=\"call_upload_sky('$redirect_uri')\">
			";
			echo "<br>";
			echo "<br>";
		} 
		else {
			echo "
				<input type='button' class='btn btn-xs btn-info' value='LOGIN' onclick=\"loginClick('$login_url_php_sky')\">
			";
		}
		?>

		<div id="table-load-sky">
			
		</div>
	</div>

	<!-- MODAL -->
	<!-- folderDetails -->
	<div id="folderModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Folder Properties</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid">
		        		<?php 
		        		echo "<p>Name: </p>";
		        		echo "<p>Description</p>";
		        		echo "<p>Owner: </p>";
		        		echo "<p>Size: </p>";
		        		echo "<p>Day created: </p>";
		        		?>
		        	</div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	
	<!-- fileDetails -->
	<div id="fileModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">File Properties</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid">
		        		<?php 
		        		echo "<p>Name: </p>";
		        		echo "<p>Description</p>";
		        		echo "<p>Owner: </p>";
		        		echo "<p>Size: </p>";
		        		echo "<p>Day created: </p>";
		        		?>
		        	</div>
		      	</div>
		      	<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- folderDelete -->
	<div id="folder_delete_modal_sky" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
		    <!-- Modal content-->
		    <div class="modal-content">
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='folder_delete_body_sky'>
		        		<p>Are you sure to delete this folder?</p>
		        		<input id="folder_delete_id_sky" type="hidden" value="">
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='folder_delete_footer_sky'>
			      	<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_folder_sky()">YES</button>
			        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- fileDelete -->
	<div id="file_delete_modal_sky" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
		    <!-- Modal content-->
		    <div class="modal-content">
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='file_delete_body_sky'>
		        		<p>Are you sure to delete this file?</p>
		        		<input id="file_delete_id_sky" type="hidden" value="">
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='file_delete_footer_sky'>
			      	<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_file_sky()">YES</button>
			        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- getLinkModal -->
	<div id="get_link_modal_sky" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Folder Properties</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='get_link_body_sky'>
		        		<form class="form-horizontal">
							<div class="form-group">
							    <div class="input-group">
							      	<div class="input-group-addon">
							      		<span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
							      	</div>
							      	<input type="text" class="form-control" id="get_link_shared_sky" value="">
							    </div>
							</div>
						</form>
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='get_link_footer_sky'>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- createFolderModal -->
	<br>
	<div id="create_folder_modal_sky" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Create folder</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='create_folder_body_sky'>
		        		<form class="form-horizontal">
							<div class="form-group">
							    <div class="input-group">
							      	<div class="input-group-addon">
							      		Name
							      	</div>
							      	<input type="text" class="form-control" id="create_folder_name_sky" placeholder="Enter folder name">
							    </div>
							</div>
						</form>
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='create_folder_footer_sky'>
		      		<button type="button" class="btn btn-sm btn-primary" onclick="call_create_folder_sky()">YES</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	
</body>
</html>