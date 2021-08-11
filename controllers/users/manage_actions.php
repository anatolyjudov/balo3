<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// выбранные параметры
	if ( array_key_exists("role_id", $_GET) && preg_match("/^\d+$/", $_GET['role_id'])) {
		$role_id = $_GET['role_id'];
	} else {
		$role_id = 0;
	}

	if ( array_key_exists("action_id", $_GET) && preg_match("/^\d+$/", $_GET['action_id'])) {
		$action_id = $_GET['action_id'];
	} else {
		$action_id = 0;
	}

	$smarty->assign_by_ref("role_id", $role_id);
	$smarty->assign_by_ref("action_id", $action_id);

	// берем из базы все что нужно
	$roles_list = users_db_get_roles_names();
	if ($roles_list == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("roles_list", $roles_list);

	$actions_list = users_db_get_actions();
	if ($actions_list == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("actions_list", $actions_list);

	if ($role_id == 0) {
		$role_id = key($roles_list);
		reset($roles_list);
	}

	if ($action_id == 0) {
		$action_id = key($actions_list);
		reset($actions_list);
	}

	// достанем права этой роли на это действие
	if (array_key_exists($role_id, $roles_list) && (array_key_exists($action_id, $actions_list))) {

		$branches = users_db_get_branches($role_id, $action_id);
		if ($branches == "error") {
			// обработка ошибки
			balo3_error("db error", true);
			exit;
		}

		$smarty->assign_by_ref("branches", $branches);

	}


	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/users/manage_actions.tpl"));

} while (false);

?>




<?php
/*

include($file_path."/includes/users/users_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	// выбранные параметры
	if ( array_key_exists("role_id", $_GET) && preg_match("/^\d+$/", $_GET['role_id'])) {
		$role_id = $_GET['role_id'];
	} else {
		$role_id = 0;
	}

	if ( array_key_exists("action_id", $_GET) && preg_match("/^\d+$/", $_GET['action_id'])) {
		$action_id = $_GET['action_id'];
	} else {
		$action_id = 0;
	}

	$smarty->assign_by_ref("role_id", $role_id);
	$smarty->assign_by_ref("action_id", $action_id);

	// берем из базы все что нужно
	$roles_list = db_users_get_roles_names();
	if ($roles_list == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("roles_list", $roles_list);

	$actions_list = db_users_get_actions();
	if ($actions_list == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("actions_list", $actions_list);

	if ($role_id == 0) {
		$role_id = key($roles_list);
		reset($roles_list);
	}

	if ($action_id == 0) {
		$action_id = key($actions_list);
		reset($actions_list);
	}

	// достанем права этой роли на это действие
	if (array_key_exists($role_id, $roles_list) && (array_key_exists($action_id, $actions_list))) {

		$branches = db_users_get_branches($role_id, $action_id);
		if ($branches == "error") {
			// обработка ошибки
			echo $_ERRORS['DB_ERROR'];
			exit;
		}

		$smarty->assign_by_ref("branches", $branches);

	}


	// выдаем шаблон
	out_main($smarty->fetch("users/manage_actions.tpl"));
*/
?>