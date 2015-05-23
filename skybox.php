<?php					
	include('library/BoxAPI.class.php');
	include('library/BoxAPI.init.php');
	include("library/SkyAPI.class.php");
	include("library/SkyAPI.init.php");
	
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
	<link rel="stylesheet" href="style-skybox.css">
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/BoxAPI.init.js"></script>
	<script src="js/SkyAPI.init.js"></script>
	<script>
		function log_out_user() {
			$.ajax({
				url : 'ajax.php',
				type : 'post',
				dataType : 'text',
				data : {
					logout_user : 1
				},
				success : function (result)
				{
					if (result) {
						var r = window.confirm(result + '\n' + "Press OK to return to Login page");
						if (r == true) {
							window.location.href = "index.php";
						}
					}
				}
			});
		}
	</script>
</head>
<body>
	<nav class="navbar navbar-default">
	  	<div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	   		<div class="navbar-header">
	      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        	<span class="sr-only">Toggle navigation</span>
		        	<span class="icon-bar"></span>
		        	<span class="icon-bar"></span>
		        	<span class="icon-bar"></span>
	      		</button>
	      		<a class="navbar-brand" href="#">SkyBox</a>
	    	</div>

	    	<!-- Collect the nav links, forms, and other content for toggling -->
	    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      		

		     	<ul class="nav navbar-nav navbar-right">
			        <li><a href="#">
			        	<span class="glyphicon glyphicon-question-sign"></span>
			        </a></li>
			        <li class="dropdown">
			          	<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php echo "$username"; ?><span class="caret"></span></a>
			          	<ul class="dropdown-menu" role="menu">
				            <li><a href="#">Account Overiew</a></li>
				            <li class="divider"></li>
				            <li><a href="#" onclick="log_out_user(); return false; ">Log Out</a></li>
			          	</ul>
			        </li>
		      	</ul>
	    	</div><!-- /.navbar-collapse -->
	  	</div><!-- /.container-fluid -->
	</nav>

	<div class="container-fluid">
		<div class="row">
			<div id="box-region" class="col-md-5">
			<!-- Get redirect URI  -->
				<?php 
				if (!empty($box->access_token)) {
					echo "<input type='hidden' value = \"$redirect_uri\" id='getRedirectURI'>";
					echo "
							<h3 class='text-center'>Welcome back</h3>
							<p class='text-center'>$user_info_box->name</p>
												
					";
					echo "
						<input type='button' class='btn btn-xs btn-danger center-block' value='LOG OUT' onclick=\"ajax_logout_box('$redirect_uri')\">
					";
					echo "
						<img class='center-block' src='images/box_btn.png'>
					";
					echo "
						<input type='file' name='upload_box' id='upload_box' onchange='getFileName(this)'>

					";
					echo "
						<input type='button' class='btn btn-xs btn-info' value='Upload' onclick=\"call_upload_box()\">
					";
					echo "<br>";
					echo "<br>";
					echo "
						<button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#create_folder_modal_box'>New Folder	</button>
					";
				}
				else {
					$login_url_php_box = $box->getLoginUrl();
					echo "
						<button type='button' class='btn btn-xs btn-default center-block' onclick=\"loginClick('$login_url_php_box')\">
							<img src='images/box_icon.png'> Box NET
						</button>
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
						<div class='center-block'>
							<h3 class='text-center'>Welcome back</h3>
							<p class='text-center'>$username_sky</p>
						</div>
					";
					echo "
						<input type='button' class='btn btn-xs btn-danger center-block' value='LOG OUT' onclick=\"ajax_logout_sky('$redirect_uri')\">
					";
					echo "
						<img class='center-block' src='images/onedrive_btn.png'>
					";
					echo "
						<input type='file' name='upload_sky' id='upload_sky' onchange='getFileName(this)'>
					";

					echo "
						<input type='button' class='btn btn-xs btn-info' value='Upload' onclick=\"call_upload_sky()\">
					";
					echo "<br>";
					echo "<br>";
					echo "
						<button type='button' class='btn btn-xs btn-info' data-toggle='modal' data-target='#create_folder_modal_sky'>New Folder</button>
					";
				} 
				else {
					echo "
						<button type='button' class='btn btn-xs btn-default center-block' onclick=\"loginClick('$login_url_php_sky')\">
							<img src='images/onedrive_icon.png'> One Drive
						</button>
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