<?php

require_once("$entrypoint_path/components/meta/meta_init.php");

do {

	if (isset($_GET['meta_id']) && preg_match("/^\d+$/", $_GET['meta_id'])) {
		$action = "delete";
		$meta_id = $_GET['meta_id'];
	}

	if (isset($_POST['meta_id']) && preg_match("/^\d+$/", $_POST['meta_id'])) {
		$action = "remove";
		$meta_id = $_POST['meta_id'];
	}

	if ($action == "undefined") {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	$smarty->assign_by_ref("meta_id", $meta_id);

	$new_meta_info = meta_db_get_metainfo_by_id($meta_id);
	if ($new_meta_info === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($new_meta_info === "notfound") {
		// ��������� ������
		balo3_error($_META_ERRORS['NO_SUCH_META'], true);
		exit;
	}

	// ����� �?
	if (!users_db_check_rights(users_get_user_id(), "CHANGE_METAINFO", $new_meta_info['URI'])) {
		balo3_error403();
		exit;
	}

	// ���� ���� ������ ��������������, �� ����������
	if ($action == "delete") {
		$smarty->assign_by_ref("meta_info", $new_meta_info);
		balo3_controller_output($smarty->fetch("$templates_path/meta/delete.tpl"));
		break;
	}

	// �������
	$meta_id = meta_db_remove_metainfo($meta_id);
	if ($meta_id === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/meta/remove.tpl"));

} while (false);

?>



<?php
/*
# delete.php
# ������� ���������� META ��� ��������
# ������������ ������� CHANGE_METAINFO

include($file_path."/includes/meta/meta_config.php");
include($file_path."/includes/meta/meta_db.php");
include($file_path."/includes/meta/meta_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_META_ERRORS;

	if (isset($_GET['meta_id']) && preg_match("/^\d+$/", $_GET['meta_id'])) {
		$action = "delete";
		$meta_id = $_GET['meta_id'];
	}

	if (isset($_POST['meta_id']) && preg_match("/^\d+$/", $_POST['meta_id'])) {
		$action = "remove";
		$meta_id = $_POST['meta_id'];
	}

	if ($action == "undefined") {
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	$smarty->assign_by_ref("meta_id", $meta_id);

	$new_meta_info = meta_db_get_metainfo_by_id($meta_id);
	if ($new_meta_info === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($new_meta_info === "notfound") {
		echo $_META_ERRORS['NO_SUCH_META'];
		exit;
	}

	// ����� �?
	if (!db_check_rights(get_user_id(), "CHANGE_METAINFO", $new_meta_info['URI'])) {
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// ���� ���� ������ ��������������, �� ����������
	if ($action == "delete") {
		$smarty->assign_by_ref("meta_info", $new_meta_info);
		out_main($smarty->fetch("meta/delete.tpl"));
	}

	// �������
	$meta_id = meta_db_remove_metainfo($meta_id);
	if ($new_meta_info === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// ������ ��� � ������
	out_main($smarty->fetch("meta/remove.tpl"));
*/
?>