<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ��������
	if ( array_key_exists("username", $_POST) && ($_POST['username']!="") ) {
	} else {
		// ��������� ������
		balo3_error($_USERS_ERRORS['EMPTY_USER_NAME'], true);
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	// ������� ���������� � �����
	$user_info = users_db_get_user_info($_POST['username']);
	if ($user_info == "error") {
		// ��������� ������
		balo3_error($_USERS_ERRORS['EMPTY_ROLE_NAME'], true);
		exit;
	}
	if ($user_info == "notfound") {
		// ��������� ������
		balo3_error($_USERS_ERRORS['NO_SUCH_USER'], true);
		exit;
	}

	// ��������� � ����
	$status = users_db_assign_role($user_info['USER_ID'], $_POST['role_id']);
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
	if ( array_key_exists("username", $_POST) && ($_POST['username']!="") ) {
	} else {
		// ��������� ������
		echo $_USERS_ERRORS['EMPTY_USER_NAME'];
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// ��������� ������
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// ������� ���������� � �����
	$user_info = db_users_get_user_info($_POST['username']);
	if ($user_info == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($user_info == "notfound") {
		// ��������� ������
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;
	}

	// ��������� � ����
	$status = db_users_assign_role($user_info['USER_ID'], $_POST['role_id']);
	if ($status == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// ������ ������
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>