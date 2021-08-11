<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ��������� ���������
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) && ($_POST['role_id'] != 0)) {
		$role_id = $_POST['role_id'];
	} else {
		balo3_error($_USERS_ERRORS['ROLE_ID_NOT_DEFINED'], true);
		exit;
	}

	// ��������
	$status = users_db_remove_role($role_id);
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

	//show_ar($_POST);
	// ��������� ���������
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) && ($_POST['role_id'] != 0)) {
		$role_id = $_POST['role_id'];
	} else {
		echo $_USERS_ERRORS['ROLE_ID_NOT_DEFINED'];
		exit;
	}

	// ��������
	$status = db_users_remove_role($role_id);
	if ($status == "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// ������ ������
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>