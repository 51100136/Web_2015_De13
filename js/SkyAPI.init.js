// MODAL ONCLICK
$(document).on("click", ".folder_delete_put_sky", function() {
	var folderID = $(this).data('id');
	$("#folder_delete_body_sky #folder_delete_id_sky").val(folderID);
});

$(document).on("click", ".file_delete_put_sky", function() {
	var folderID = $(this).data('id');
	$("#file_delete_body_sky #file_delete_id_sky").val(folderID);
});

$(document).on("click", ".get_link_put_sky", function() {
	var folderID = $(this).data('id');
	$("#get_link_body_sky #get_link_shared_sky").val(folderID);
});

// LOG IN
function loginClick(loginUrl){
	window.location.href = loginUrl;
}

// AJAX LOG OUT
function ajax_logout_sky(redirectURL) {
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			logout_sky : 1
		},
		success : function (result)
		{
			if (result) {
				var r = window.confirm(result + '\n' + "Press OK to return to Login page");
				if (r == true) {
					window.location.href = redirectURL;
				}
			}
		}
	});	
}

 // AJAX NEW FOLDER
function ajax_create_folder_sky(foldername, folderid) {
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'html',
		data : {
			create_folder_sky : 1,
			folder_name_sky : foldername,
			id_sky : folderid
		},
		success : function (result)
		{
			document.getElementById("create_folder_body_sky").innerHTML = result;
			var create_folder_footer = '<button type="button" class="btn btn-sm btn-primary" onclick=' + '"repeat_ajax_create_folder_sky(' + "'" + folderid + "'" + ')"' + ' data-dismiss="modal">OK</button>';
			document.getElementById("create_folder_footer_sky").innerHTML = create_folder_footer;
		}
	});
}
function call_create_folder_sky() {
	var folder_name = $('#create_folder_name_sky').val();
	var folder_id = $('#cur_folder_id_sky').val();
	ajax_create_folder_sky(folder_name, folder_id);
}


// AJAX DELETE FOLDER
function ajax_delete_folder_sky(deletefolderid, folderid) {
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'html',
		data : {
			delete_folder_sky : 1,
			id_sky : deletefolderid
		},
		success : function (result)
		{
			document.getElementById("folder_delete_body_sky").innerHTML = result;
			var folder_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick=' + '"repeat_ajax_load_folder_sky(' + "'" + folderid + "'" + ')"' + ' data-dismiss="modal">OK</button>';
			document.getElementById("folder_delete_footer_sky").innerHTML = folder_delete_footer;
		}
	});
}
function call_delete_folder_sky() {
	var fi = $('#folder_delete_id_sky').val();
	var curfi = $('#cur_folder_id_sky').val();
	ajax_delete_folder_sky(fi, curfi);
}

// AJAX DELETE FILE
function ajax_delete_file_sky(deletefileid, folderid) {
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'html',
		data : {
			delete_file_sky : 1,
			id_sky : deletefileid
		},
		success : function (result)
		{
			document.getElementById("file_delete_body_sky").innerHTML = result;
			var folder_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick=' + '"repeat_ajax_load_file_sky(' + "'" + folderid + "'" + ')"' + ' data-dismiss="modal">OK</button>';
			document.getElementById("file_delete_footer_sky").innerHTML = folder_delete_footer;
		}
	});
}
function call_delete_file_sky() {
	var fi = $('#file_delete_id_sky').val();
	var curfi = $('#cur_folder_id_sky').val();
	ajax_delete_file_sky(fi, curfi);
}

// AJAX DOWNLOAD FILE
function call_download_sky(source) {
	window.open(source);
}

// AJAX UPLOAD FILE
function ajax_upload_sky(filename, folderid)
{	
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			upload_sky 	: 1,
			file_sky 	: filename,
			id_sky 		: folderid
		},
		success : function (result)
		{
			if (result) {
				var r = window.alert(result + '\n' + "Press OK to return");
			}
			$('#table-load-sky').html("");
			ajax_load_sky(folderid);
		}
	});	
}
function call_upload_sky() {
	var fn = $('#upload_sky').val();
	if (fn != "") {
		var filename = fn.replace(/^C:\\fakepath\\/, "");
		var fullfn = 'C:\\Upload\\' + filename;
		var fi = $('#cur_folder_id_sky').val();
		ajax_upload_sky(fullfn, fi);
	}
}


// AJAX LOAD PAGE
function ajax_load_sky(folderid) 
{
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			load_sky : 1,
			id_sky : folderid
		},
		success : function (result)
		{
			$('#table-load-sky').html(result);
		}
	});
}

$(document).ready(function() {
	var root_folder_id_sky = $('#root_folder_id_sky').val()
	ajax_load_sky(root_folder_id_sky);
});

function reload_folder_modal_sky() {
	var repeat_folder_delete_body = '<p>Are you sure to delete this folder?</p>' + '<input id="folder_delete_id_sky" type="hidden" value="">';
	document.getElementById("folder_delete_body_sky").innerHTML = repeat_folder_delete_body;
	var repeat_folder_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_folder_sky()">YES</button>' + '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>';
	document.getElementById("folder_delete_footer_sky").innerHTML = repeat_folder_delete_footer;
}

function repeat_ajax_load_folder_sky(folderid) {
	$('#folder_delete_modal_sky').modal('hide');
	$('#table-load-sky').html("");
	ajax_load_sky(folderid);
	setTimeout('reload_folder_modal_sky()', 1000);
}

function reload_file_modal_sky() {
	var repeat_file_delete_body = '<p>Are you sure to delete this file?</p>' + '<input id="file_delete_id_sky" type="hidden" value="">';
	document.getElementById("file_delete_body_sky").innerHTML = repeat_file_delete_body;
	var repeat_file_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_file_sky()">YES</button>' + '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>';
	document.getElementById("file_delete_footer_sky").innerHTML = repeat_file_delete_footer;
}

function repeat_ajax_load_file_sky(folderid) {
	$('#file_delete_modal_sky').modal('hide');
	$('#table-load-sky').html("");
	ajax_load_sky(folderid);
	setTimeout('reload_file_modal_sky()', 1000);
}

function reload_create_modal_sky() {
	var repeat_create_folder_body = '<form class="form-horizontal">' +
										'<div class="form-group">' +
										    '<div class="input-group">' +
										      	'<div class="input-group-addon">' +
										      		'Name' +
										      	'</div>' +
										      	'<input type="text" class="form-control" id="create_folder_name_sky" placeholder="Enter folder name">' +
										    '</div>' +
										'</div>' +
									'</form>';
	document.getElementById("create_folder_body_sky").innerHTML = repeat_create_folder_body;
	var repeat_create_folder_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_create_folder_sky()">YES</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
	document.getElementById("create_folder_footer_sky").innerHTML = repeat_create_folder_footer;
}

function repeat_ajax_create_folder_sky(folderid) {
	$('#create_folder_modal_sky').modal('hide');
	$('#table-load-sky').html("");
	ajax_load_sky(folderid);
	setTimeout('reload_create_modal_sky()', 1000);
}