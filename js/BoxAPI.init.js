// Init JS
$(document).on("click", ".folder_delete_put_box", function() {
	var folderID = $(this).data('id');
	$("#folder_delete_body_box #folder_delete_id_box").val(folderID);
});
$(document).on("click", ".file_delete_put_box", function() {
	var folderID = $(this).data('id');
	$("#file_delete_body_box #file_delete_id_box").val(folderID);
});
$(document).on("click", ".get_link_put_box", function() {
	var folderID = $(this).data('id');
	$("#get_link_body_box #get_link_shared_box").val(folderID);
	var value = $('#get_link_shared_box').val();
});
$(document).on("click", ".folder_info_put_box", function() {
	var folder_name = $(this).data('name');
	var folder_description = $(this).data('description');
	var folder_owner = $(this).data('owner');
	var folder_day = $(this).data('day');
	var folder_size = $(this).data('size');
	
	$('#folder_info_name_box').html(folder_name);
	$('#folder_info_description_box').html(folder_description);
	$('#folder_info_owner_box').html(folder_owner);
	$('#folder_info_day_box').html(folder_day);
	$('#folder_info_size_box').html(folder_size);
});



// Reload page
function reload_page_box(URL) {
	window.location.href = URL;
}

// LOGIN
function loginClick(loginUrl){
	window.location.href = loginUrl;
}

// LOGOUT
function ajax_logout_box(redirectURL)
{
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			logout_box : 1
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

// CREATE FOLDER
function ajax_create_folder_box(folderName, folderid)
{
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			create_folder_box 	: 1,
			folder_name_box		: folderName,
			id_box 				: folderid
		},
		success : function (result)
		{
			if (result) {
				document.getElementById("create_folder_body_box").innerHTML = result;
				var create_folder_footer = '<button type="button" class="btn btn-sm btn-primary" onclick=' + '"repeat_ajax_create_folder_box(' + "'" + folderid + "'" + ')"' + ' data-dismiss="modal">OK</button>';
				document.getElementById("create_folder_footer_box").innerHTML = create_folder_footer;
			}
		}
	});	
}
function call_create_folder_box() {
	var folder_name = $('#create_folder_name_box').val();
	var folder_id = $('#cur_folder_id_box').val();
	ajax_create_folder_box(folder_name, folder_id);
}

// UPLOAD TEST.TXT
function ajax_upload_box(filename, folderid)
{	
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			upload_box 	: 1,
			file_box 	: filename,
			id_box 		: folderid
		},
		success : function (result)
		{
			if (result) {
				var r = window.alert(result + '\n' + "Press OK to return");
			}
			ajax_load_box(folderid);
		}
	});	
}
function call_upload_box() {
	var fn = $('#upload_box').val();
	if (fn != "") {
		var filename = fn.replace(/^C:\\fakepath\\/, "");
		var fullfn = 'C:\\Upload\\' + filename;
		var fi = $('#cur_folder_id_box').val();
		ajax_upload_box(fullfn, fi);
	}
}

// DOWNLOAD
function ajax_download_box(fileid) {
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			download_box 	: 1,
			id_box 		: fileid
		},
		success : function (result)
		{
			//window.alert(result);
			if (result == "-1") window.alert('File not exist');
			else window.open(result);
		}
	});
}
function call_download_box(fileid) {
	ajax_download_box(fileid);
}

// DELETE FOLDER
function ajax_delete_folder_box(fileid, folderid)
{
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'html',
		data : {
			del_folder_box : 1,
			id_box : fileid
		},
		success : function (result)
		{
			document.getElementById("folder_delete_body_box").innerHTML = result;
			var folderDeleteFooter = '<button type="button" class="btn btn-sm btn-primary" onclick=' + "'repeat_ajax_load_folder_box(" + folderid + ")'" + ' data-dismiss="modal">OK</button>';
			document.getElementById("folder_delete_footer_box").innerHTML = folderDeleteFooter;
		}
	});	
}
function call_delete_folder_box()
{
	var fi = $('#folder_delete_id_box').val();
	var curfi = $('#cur_folder_id_box').val();
	ajax_delete_folder_box(fi, curfi);
}

// DELETE FILE
function ajax_delete_file_box(fileid, folderid)
{	
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'html',
		data : {
			del_file_box : 1,
			id_box : fileid
		},
		success : function (result)
		{
			document.getElementById("file_delete_body_box").innerHTML = result;
			var fileDeleteFooter = '<button type="button" class="btn btn-sm btn-primary" onclick=' + "'repeat_ajax_load_file_box(" + folderid + ")'" + ' data-dismiss="modal">OK</button>';
			document.getElementById("file_delete_footer_box").innerHTML = fileDeleteFooter;
		}
	});	
}
function call_delete_file_box()
{
	var fi = $('#file_delete_id_box').val();
	var curfi = $('#cur_folder_id_box').val();
	ajax_delete_file_box(fi, curfi);
}

// LOAD PAGE
function ajax_load_box(folderid) 
{
	$.ajax({
		url : 'ajax.php',
		type : 'post',
		dataType : 'text',
		data : {
			load_box : 1,
			id_box : folderid
		},
		success : function (result)
		{
			$('#table-load-box').html(result);
		}
	});
}
$(document).ready(function() {
	ajax_load_box(0);
});

function reload_folder_modal_box() {
	var repeat_folder_delete_body = '<p>Are you sure to delete this folder?</p>' + '<input id="folder_delete_id_box" type="hidden" value="">';
	document.getElementById("folder_delete_body_box").innerHTML = repeat_folder_delete_body;
	var repeat_folder_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_folder_box()">YES</button>' + '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>';
	document.getElementById("folder_delete_footer_box").innerHTML = repeat_folder_delete_footer;
}

function repeat_ajax_load_folder_box(folderid) {
	$('#folder_delete_modal_box').modal('hide');
	$('#table-load-box').html("");
	ajax_load_box(folderid);
	setTimeout('reload_folder_modal_box()', 1000);
}

function reload_file_modal_box() {
	var repeat_file_delete_body = '<p>Are you sure to delete this file?</p>' + '<input id="file_delete_id_box" type="hidden" value="">';
	document.getElementById("file_delete_body_box").innerHTML = repeat_file_delete_body;
	var repeat_file_delete_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_delete_file_box()">YES</button>' + '<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">NO</button>';
	document.getElementById("file_delete_footer_box").innerHTML = repeat_file_delete_footer;
}

function repeat_ajax_load_file_box(folderid) {
	$('#file_delete_modal_box').modal('hide');
	$('#table-load-box').html("");
	ajax_load_box(folderid);
	setTimeout('reload_file_modal_box()', 1000);
}

function reload_create_modal_box() {
	var repeat_create_folder_body = '<form class="form-horizontal">' +
										'<div class="form-group">' +
										    '<div class="input-group">' +
										      	'<div class="input-group-addon">' +
										      		'Name' +
										      	'</div>' +
										      	'<input type="text" class="form-control" id="create_folder_name_box" placeholder="Enter folder name">' +
										    '</div>' +
										'</div>' +
									'</form>';
	document.getElementById("create_folder_body_box").innerHTML = repeat_create_folder_body;
	var repeat_create_folder_footer = '<button type="button" class="btn btn-sm btn-primary" onclick="call_create_folder_box()">YES</button><button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
	document.getElementById("create_folder_footer_box").innerHTML = repeat_create_folder_footer;
}

function repeat_ajax_create_folder_box(folderid) {
	$('#create_folder_modal_box').modal('hide');
	$('#table-load-box').html("");
	ajax_load_box(folderid);
	setTimeout('reload_create_modal_box()', 1000);
}