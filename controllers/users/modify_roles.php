<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ���������
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
		$role_id = $_POST['role_id'];
	} else {
		// ��������� ������
		balo3_error($_USERS_ERRORS['ROLE_ID_NOT_DEFINED'], true);
		exit;
	}

	if ( array_key_exists("role_name", $_POST) && ($_POST['role_name'] != "") ) {
		$role_name = $_POST['role_name'];
	} else {
		// ��������� ������
		balo3_error($_USERS_ERRORS['EMPTY_ROLE_NAME'], true);
		exit;
	}

	// ��������
	$status = users_db_modify_role($role_id, $role_name);
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

	// ��������� ���������
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
		$role_id = $_POST['role_id'];
	} else {
		return out_error($_USERS_ERRORS['ROLE_ID_NOT_DEFINED']);
	}

	if ( array_key_exists("role_name", $_POST) && ($_POST['role_name'] != "") ) {
		$role_name = $_POST['role_name'];
	} else {
		return out_error($_USERS_ERRORS['EMPTY_ROLE_NAME']);
	}

	// ��������
	$status = db_users_modify_role($role_id, $role_name);
	if ($status == "error") {
		return out_error($_ERRORS['DB_ERROR']);
	}

	// ������ ������
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>