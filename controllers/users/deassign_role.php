<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ��������
	if ( array_key_exists("user_id", $_POST) && preg_match("/^\d+$/", $_POST['user_id']) ) {
	} else {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	// ������� ��������
	$status = users_db_deassign_role($_POST['user_id'], $_POST['role_id']);
	if ($status == "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback.tpl"));

} while (false);

?>




<?php
/*
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_USERS_ERRORS;

	// ��������� ��������
	if ( array_key_exists("user_id", $_POST) && preg_match("/^\d+$/", $_POST['user_id']) ) {
	} else {
		// ��������� ������
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// ��������� ������
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// ������� ��������
	$status = db_users_deassign_role($_POST['user_id'], $_POST['role_id']);
	if ($status == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// ������ ������
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>