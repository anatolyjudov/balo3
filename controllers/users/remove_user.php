<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ���������

	// ��������� id ������������ � �������� ���������
	if (isset($_POST['user_id']) && preg_match("/^\d+$/", $_POST['user_id'])) {
		$user_id = $_POST['user_id'];
	} else {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	// ������� ���������� � ������������
	$old_user_info = users_db_get_user_info($user_id);
	if ($old_user_info == "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($old_user_info == "notfound") {
		// ��������� ������
		balo3_error($_USERS_ERRORS['NO_SUCH_USER'], true);
		exit;
	}
	$smarty->assign_by_ref("user_info", $old_user_info);

	// ������� ������������
	$status = users_db_remove_user($old_user_info['USER_ID']);
	if ($status === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/remove_user.tpl"));

} while (false);

?>



<?php
/*
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;
global $ldap_status_values;

	// ��������� ���������

	// ��������� id ������������ � �������� ���������
	if (isset($_POST['user_id']) && preg_match("/^\d+$/", $_POST['user_id'])) {
		$user_id = $_POST['user_id'];
	} else {
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// ������� ���������� � ������������
	$old_user_info = db_user_get_from_common($user_id);
	if ($old_user_info == "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($old_user_info == "notfound") {
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;
	}
	$smarty->assign_by_ref("user_info", $old_user_info);

	// ������� ������������
	$status = db_users_remove_user_common($old_user_info['USER_ID']);
	if ($status === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// ������ ������
	out_main($smarty->fetch("users/remove_common_user.tpl"));
*/
?>