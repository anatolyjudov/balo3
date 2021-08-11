<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� id ������������ � �������� ���������
	if (isset($_GET['user_id']) && preg_match("/^\d+$/", $_GET['user_id'])) {
		$user_id = $_GET['user_id'];
	} else {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	// ������� ���������� � ������������
	$user_info = users_db_get_user_info($user_id);
	if ($user_info == "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($user_info == "notfound") {
		// ��������� ������
		balo3_error($_USERS_ERRORS['NO_SUCH_USER'], true);
		exit;
	}
	$smarty->assign_by_ref("user_info", $user_info);


	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/edit_user.tpl"));

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

	// ��������� id ������������ � �������� ���������
	if (isset($_GET['user_id']) && preg_match("/^\d+$/", $_GET['user_id'])) {
		$user_id = $_GET['user_id'];
	} else {
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// ������� ���������� � ������������
	$user_info = db_user_get_from_common($user_id);
	if ($user_info == "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($user_info == "notfound") {
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;
	}
	$smarty->assign_by_ref("user_info", $user_info);

	// ������ ������
	out_main($smarty->fetch("users/edit_common_user.tpl"));
*/
?>