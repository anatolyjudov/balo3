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
	$smarty->assign_by_ref("old_user_info", $old_user_info);

	$user_info['USER_ID'] = $old_user_info['USER_ID'];

	// �����
	if (isset($_POST['user_name'])) {
		$user_info['USER_NAME'] = $_POST['user_name'];
	} else {
		$user_info['USER_NAME'] = "";
	}

	// ��� �����������
	if ( isset($_POST['auth_type']) && (($_POST['auth_type'] == "db") || ($_POST['auth_type'] == "ldap")) ) {
		$user_info['AUTH_TYPE'] = $_POST['auth_type'];
	} else {
		$user_info['AUTH_TYPE'] = "";
	}

	// ������
	if (isset($_POST['user_password'])) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// �������
	if (isset($_POST['user_nickname'])) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}

	// ��������� ���
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}

	// ����� email
	if (isset($_POST['user_email'])) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}

	// ����� icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}
	
	// ����������� ��
	if (isset($_POST['activated']) and $_POST['activated'] == 1) {
		$user_info['ACTIVE'] = 1;
	} else {
		$user_info['ACTIVE'] = 0;
	}

	// ����� � ������
	$smarty->assign_by_ref("user_info", $user_info);

	// ��������
	if ($user_info['USER_NAME'] == "") {
		$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_USER_NAME']);
		balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
		break;
	}
	if ($user_info['USER_NAME'] != $old_user_info['USER_NAME']) {
		// ���� � ����� ���� �������������
		$test_user_info = users_db_get_user_info($user_info['USER_NAME']);
		if ($test_user_info == "error") {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
			break;
		}
		// �������� �� ������������ �����
		if ($test_user_info != "notfound") {
			$smarty->assign("errmsg", $_USERS_ERRORS['USER_EXISTS']);
			balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
			break;
		}
	}
	if ($user_info['AUTH_TYPE'] == "") {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
		break;
	}

	// �������� ������������ ���������� ��������
	if ($user_info['USER_NICKNAME'] != $old_user_info['USER_NICKNAME']) {
		$status = users_db_nickname_exists($user_info['USER_NICKNAME']);
		if ($status === "error") {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
			break;
		}
		if ($status) {
			$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
			balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
			break;
		}
	}

	// ������� ������������
	$status = users_db_modify_user($user_info['USER_ID'], $user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], '', '', '', '', $user_info['ACTIVE']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/users/edit_common_user.tpl"));
		exit;
	}

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/modify_user.tpl"));

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
	$smarty->assign_by_ref("old_user_info", $old_user_info);

	$user_info['USER_ID'] = $old_user_info['USER_ID'];

	// �����
	if (isset($_POST['user_name'])) {
		$user_info['USER_NAME'] = $_POST['user_name'];
	} else {
		$user_info['USER_NAME'] = "";
	}

	// ��� �����������
	if ( isset($_POST['auth_type']) && (($_POST['auth_type'] == "db") || ($_POST['auth_type'] == "ldap")) ) {
		$user_info['AUTH_TYPE'] = $_POST['auth_type'];
	} else {
		$user_info['AUTH_TYPE'] = "";
	}

	// ������
	if (isset($_POST['user_password'])) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// �������
	if (isset($_POST['user_nickname'])) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}

	// ��������� ���
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}

	// ����� email
	if (isset($_POST['user_email'])) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}

	// ����� icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}      
	
	// ����������� ��
	if (isset($_POST['activated']) and $_POST['activated'] == 1) {
		$user_info['ACTIVE'] = 1;
	} else {
		$user_info['ACTIVE'] = 0;
	}	

	// ����� � ������
	$smarty->assign_by_ref("user_info", $user_info);

	// ��������
	if ($user_info['USER_NAME'] == "") {
		$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_USER_NAME']);
		out_main($smarty->fetch("users/edit_common_user.tpl"));
		exit;
	}
	if ($user_info['USER_NAME'] != $old_user_info['USER_NAME']) {
		// ���� � ����� ���� �������������
		$test_user_info = db_user_get_from_common($user_info['USER_NAME']);
		if ($test_user_info == "error") {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("users/edit_common_user.tpl"));
			exit;
		}
		// �������� �� ������������ �����
		if ($test_user_info != "notfound") {
			$smarty->assign("errmsg", $_USERS_ERRORS['USER_EXISTS']);
			out_main($smarty->fetch("users/edit_common_user.tpl"));
			exit;
		}
	}
	if ($user_info['AUTH_TYPE'] == "") {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		out_main($smarty->fetch("users/edit_common_user.tpl"));
		exit;
	}

	// �������� ������������ ���������� ��������
	if ($user_info['USER_NICKNAME'] != $old_user_info['USER_NICKNAME']) {
		$status = db_user_common_nickname_exists($user_info['USER_NICKNAME']);
		if ($status === "error") {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("users/edit_common_user.tpl"));
			exit;
		}
		if ($status) {
			$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
			out_main($smarty->fetch("users/edit_common_user.tpl"));
			exit;
		}
	}

	// ������� ������������
	$status = db_users_modify_user_common($user_info['USER_ID'], $user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], '', '', '', '', $user_info['ACTIVE']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/edit_common_user.tpl"));
		exit;
	}

	// ������ ������
	out_main($smarty->fetch("users/modify_common_user.tpl"));
*/
?>