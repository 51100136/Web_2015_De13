<?php					
	include('library/BoxAPI.class.php');
	include('library/BoxAPI.init.php');
	include_once("library/SkyAPI.class.php");
	include_once("library/SkyAPI.init.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SkyBox Transfer</title>
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
	<script type="text/javascript" src="js/BoxAPI.init.js"></script>
	<script type="text/javascript" src="js/SkyAPI.init.js"></script>
</head>
<body>
	<div class="container">
		<div class="row">
			<div id="box-region" class="col-md-5">
			<!-- Get redirect URI  -->
				<?php 
				if (!empty($box->access_token)) {
					echo "<input type='hidden' value = \"$redirect_uri\" id='getRedirectURI'>";
					echo "
						<h3>Welcome back</h3>
						<br>
						$user_info_box->name
					";
					echo "
						<input type='button' class='btn btn-xs btn-danger' value='LOG OUT' onclick=\"ajax_logout_box('$redirect_uri')\">
					";
					echo "<br>";
					echo "<br>";
					echo "
						<input type='button' class='btn btn-xs btn-info' value='New Folder' data-toggle='modal' data-target='#create_folder_modal_box'>
					";
					echo "<br>";
					echo "<br>";
					echo "
						<input type='file' name='upload_box' id='upload_box' onchange='getFileName(this)'>
					";

					echo "
						<input type='button' class='btn btn-xs btn-info' value='Upload' onclick=\"call_upload_box()\">
					";
					echo "<br>";
					echo "<br>";
				}
				else {
					$login_url_php_box = $box->getLoginUrl();
					echo "
						<input type='button' class='btn btn-xs btn-info' value='LOGIN' onclick=\"loginClick('$login_url_php_box')\">
					";
				}
				?>
				
				<!-- TABLE TO FETCH DATA FROM BOX -->
				<div id="table-load-box">
					
				</div>
			</div>
			<!-- End #box -->
			
			<div id="center-region" class="col-md-1">
				
			</div>

			<div id="sky-region" class="col-md-5">
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
						<input type='file' name='upload_sky' id='upload_sky' onchange='getFileName(this)'>
					";

					echo "
						<input type='button' class='btn btn-xs btn-info' value='Upload' onclick=\"call_upload_sky()\">
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
			<!-- End #sky-regio n-->
		</div>
	</div>

	<!-- folderDetails -->
	<div id="folder_info_modal_box" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Properties</h4>
		     	</div>
		     	 <div class="modal-body" id="folder_info_body_box">
		        	<div class="container-fluid">
		        		<form class="form-horizontal">
							<div class="form-group">
							    <label class="col-md-3 control-label">Name</label>
							    <div class="col-sm-9">
							      	<p class="form-control-static" id="folder_info_name_box"></p>
							    </div>
							</div>
							<!-- End Folder Name group -->

							<div class="form-group">
							    <label class="col-md-3 control-label">Description</label>
							    <div class="col-sm-9">
							      	<p class="form-control-static" id="folder_info_description_box"></p>
							    </div>
							</div>
							<!-- End Discription group -->

							<div class="form-group">
							    <label class="col-md-3 control-label">Owner</label>
							    <div class="col-sm-9">
							      	<p class="form-control-static" id="folder_info_owner_box"></p>
							    </div>
							</div>
							<!-- End Owner group -->

							<div class="form-group">
							    <label class="col-md-3 control-label">Day Created</label>
							    <div class="col-sm-9">
							      	<p class="form-control-static" id="folder_info_day_box"></p>
							    </div>
							</div>
							<!-- End Day-Created grop -->

							<div class="form-group">
							    <label class="col-md-3 control-label">Size</label>
							    <div class="col-sm-9">
							      	<p class="form-control-static" id="folder_info_size_box"></p>
							    </div>
							</div>
							<!-- End Size group -->
						</form>
		        	</div>
		      	</div>
		      	<div class="modal-footer" id="folder_info_footer_box">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<!-- End folderDetails Modal -->
	
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
	<!-- End fileDetails Modal -->

	<!-- BOX MODAL -->
	<!-- folderDelete -->
	<div id="folder_delete_modal_box" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
		    <!-- Modal content-->
		    <div class="modal-content">
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='folder_delete_body_box'>
		        		<p>Are you sure to delete this folder?</p>
		        		<input id="folder_delete_id_box" type="hidden" value="">
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='folder_delete_footer_box'>
		      	<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_folder_box()">YES</button>
		        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- fileDelete -->
	<div id="file_delete_modal_box" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
		    <!-- Modal content-->
		    <div class="modal-content">
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='file_delete_body_box'>
		        		<p>Are you sure to delete this file?</p>
		        		<input id="file_delete_id_box" type="hidden" value="">
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='file_delete_footer_box'>
		      	<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_file_box()">YES</button>
		        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- getLinkModal -->
	<div id="get_link_modal_box" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Folder Properties</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='get_link_body_box'>
		        		<form class="form-horizontal">
							<div class="form-group">
							    <div class="input-group">
							      	<div class="input-group-addon">
							      		<span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
							      	</div>
							      	<input type="text" class="form-control" id="get_link_shared_box" value="">
							    </div>
							</div>
						</form>
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='get_link_footer_box'>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>

	<!-- createFolderModal -->
	<div id="create_folder_modal_box" class="modal fade" role="dialog">
		<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content">
		      	<div class="modal-header">
		        	<button type="button" class="close" data-dismiss="modal">&times;</button>
		        	<h4 class="modal-title">Create folder</h4>
		     	</div>
		     	 <div class="modal-body">
		        	<div class="container-fluid" id='create_folder_body_box'>
		        		<form class="form-horizontal">
							<div class="form-group">
							    <div class="input-group">
							      	<div class="input-group-addon">
							      		Name
							      	</div>
							      	<input type="text" class="form-control" id="create_folder_name_box" placeholder="Enter folder name">
							    </div>
							</div>
						</form>
		        	</div>
		      	</div>
		      	<div class="modal-footer" id='create_folder_footer_box'>
		      		<button type="button" class="btn btn-sm btn-primary" onclick="call_create_folder_box()">YES</button>
		        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      	</div>
		    </div>
	  	</div>
	</div>
	<!-- END BOX MODAL -->

	<!-- SKY MODAL -->
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
	<!-- END SKY MODAL -->
</body>
</html>