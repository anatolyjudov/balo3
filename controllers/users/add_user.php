<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// принимаем параметры
	// логин
	if (isset($_POST['user_name'])) {
		$user_info['USER_NAME'] = $_POST['user_name'];
	} else {
		$user_info['USER_NAME'] = "";
	}

	// тип авторизации
	if ( isset($_POST['auth_type']) && (($_POST['auth_type'] == "db") || ($_POST['auth_type'] == "ldap")) ) {
		$user_info['AUTH_TYPE'] = $_POST['auth_type'];
	} else {
		$user_info['AUTH_TYPE'] = "";
	}

	// пароль
	if (isset($_POST['user_password'])) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// никнейм
	if (isset($_POST['user_nickname'])) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}

	// настоящее имя
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}

	// адрес email
	if (isset($_POST['user_email'])) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}

	// адрес icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}
		
	// активирован ли
	if (isset($_POST['activated']) and $_POST['activated'] == 1) {
		$user_info['ACTIVE'] = 1;
	} else {
		$user_info['ACTIVE'] = 0;
	}

	// кладём в смарти
	$smarty->assign_by_ref("user_info", $user_info);

	// проверка
	if ($user_info['USER_NAME'] == "") {
		$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_USER_NAME']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}
	// ищем в общей базе пользователей
	$test_user_info = users_db_get_user_info($user_info['USER_NAME']);
	if ($test_user_info == "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}
	// проверка на уникальность имени
	if ($test_user_info != "notfound") {
		$smarty->assign("errmsg", $_USERS_ERRORS['USER_EXISTS']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}
	if ($user_info['AUTH_TYPE'] == "") {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}

	// проверим уникальность выбранного никнейма
	$status = users_db_nickname_exists($user_info['USER_NICKNAME']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}
	if ($status) {
		$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}

	// создаем пользователя
	// db_users_create_user_common($user_name, $auth_type = 'ldap', $user_password = '', $user_nickname = '', $user_real_name = '', $user_email = '', $user_icq = '', $user_phone = '', $user_address = '', $user_real_name_flg = 1, $user_email_flg = 1, $user_icq_flg = 1, $user_phone_flg = 1, $user_address_flg = 1, $activation_id = '', $active = 0)
	$user_id = users_db_create_user($user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], '', '', 1, 1, 1, 1, 1, '', $user_info['ACTIVE']);
	if ($user_id === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/users/new_common_user.tpl"));
		break;
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/users/add_user.tpl"));

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

	// принимаем параметры

	// логин
	if (isset($_POST['user_name'])) {
		$user_info['USER_NAME'] = $_POST['user_name'];
	} else {
		$user_info['USER_NAME'] = "";
	}

	// тип авторизации
	if ( isset($_POST['auth_type']) && (($_POST['auth_type'] == "db") || ($_POST['auth_type'] == "ldap")) ) {
		$user_info['AUTH_TYPE'] = $_POST['auth_type'];
	} else {
		$user_info['AUTH_TYPE'] = "";
	}

	// пароль
	if (isset($_POST['user_password'])) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// никнейм
	if (isset($_POST['user_nickname'])) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}

	// настоящее имя
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}

	// адрес email
	if (isset($_POST['user_email'])) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}

	// адрес icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}     
		
	// активирован ли
	if (isset($_POST['activated']) and $_POST['activated'] == 1) {
		$user_info['ACTIVE'] = 1;
	} else {
		$user_info['ACTIVE'] = 0;
	}		

	// кладём в смарти
	$smarty->assign_by_ref("user_info", $user_info);

	// проверка
	if ($user_info['USER_NAME'] == "") {
		$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_USER_NAME']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}
	// ищем в общей базе пользователей
	$test_user_info = db_user_get_from_common($user_info['USER_NAME']);
	if ($test_user_info == "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}
	// проверка на уникальность имени
	if ($test_user_info != "notfound") {
		$smarty->assign("errmsg", $_USERS_ERRORS['USER_EXISTS']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}
	if ($user_info['AUTH_TYPE'] == "") {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}

	// проверим уникальность выбранного никнейма
	$status = db_user_common_nickname_exists($user_info['USER_NICKNAME']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}
	if ($status) {
		$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}

	// создаем пользователя
	// db_users_create_user_common($user_name, $auth_type = 'ldap', $user_password = '', $user_nickname = '', $user_real_name = '', $user_email = '', $user_icq = '', $user_phone = '', $user_address = '', $user_real_name_flg = 1, $user_email_flg = 1, $user_icq_flg = 1, $user_phone_flg = 1, $user_address_flg = 1, $activation_id = '', $active = 0)
	$user_id = db_users_create_user_common($user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], '', '', 1, 1, 1, 1, 1, '', $user_info['ACTIVE']);
	if ($user_id === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/new_common_user.tpl"));
		exit;
	}

	// назначаем его модератором
	$status = db_users_assign_role($user_id, 10);			// 10- это роль модератора

	// выдаем шаблон
	out_main($smarty->fetch("users/add_common_user.tpl"));
*/
?>