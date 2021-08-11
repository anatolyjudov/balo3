<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ��������� ������������� ������ � �������
	if (isset($_GET['skip']) && preg_match("/\d+/", $_GET['skip'])) {
		$skip = $_GET['skip'];
	} else {
		$skip = 0;
	}
	$smarty->assign_by_ref("users_on_page", $users_admin_on_page);

	// ������� ���������� ������������� � ������� ���� ������
	$users_count = users_db_count_users();
	if ($users_count === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("users_count", $users_count);

	// ������� ������ �������������
	$users_list = users_db_get_users_list($skip, $users_admin_on_page);
	if ($users_count === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("users_list", $users_list);

	// ���������� � ���������
	common_generate_pages_info($skip, $users_admin_on_page, $users_count, count($users_list));

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/manage_users.tpl"));

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
global $users_admin_on_page;

	// ��������� ��������� ������������� ������ � �������
	if (isset($_GET['skip']) && preg_match("/\d+/", $_GET['skip'])) {
		$skip = $_GET['skip'];
	} else {
		$skip = 0;
	}
	$smarty->assign_by_ref("users_on_page", $users_admin_on_page);

	// ������� ���������� ������������� � ������� ���� ������
	$users_count = db_users_count_users_from_common();
	if ($users_count === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("users_count", $users_count);

	// ������� ������ �������������
	$users_list = db_users_get_users_from_common($skip, $users_admin_on_page);
	if ($users_count === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("users_list", $users_list);

	// ���������� � ���������
	generate_pages_info($skip, $users_admin_on_page, $users_count, count($users_list));

	// ������ ������
	out_main($smarty->fetch("users/manage_common_users.tpl"));
*/
?>