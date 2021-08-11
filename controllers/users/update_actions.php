<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// выбранные параметры
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id'])) {
		$role_id = $_POST['role_id'];
	} else {
		$role_id = 0;
	}

	if ( array_key_exists("action_id", $_POST) && preg_match("/^\d+$/", $_POST['action_id'])) {
		$action_id = $_POST['action_id'];
	} else {
		$action_id = 0;
	}

	$smarty->assign_by_ref("role_id", $role_id);
	$smarty->assign_by_ref("action_id", $action_id);

	// создадим массив новых прав роли на данное действие
	$new_rights = array();
	foreach ($_POST as $k=>$v) {
		if (preg_match("/^br_(\d+)$/", $k, $matches)) {
			$num = $matches[1];
			// удалено?
			if (array_key_exists('del_' . $num, $_POST) && ($_POST['del_' . $num] == "on")) continue;
			// путь
			if ($v == "") continue;
			$new_rights[$num]['affected_branch'] = $v;
			// флаг
			if (array_key_exists('type_' . $num, $_POST) && ($_POST['type_' . $num] == "on")) {
				$new_rights[$num]['right_type'] = 0;
			} else {
				$new_rights[$num]['right_type'] = 1;
			}
		}
	}

	// теперь вызываем функцию обновления прав роли
	$status = users_db_update_role_actions($role_id, $action_id, $new_rights);
	if ($status == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}

	// если все хорошо, то смотрим, добавилась ли новая ветка
	if (array_key_exists("type_new_branch", $_POST) && ($_POST['type_new_branch'] == "on")) {
		$right_type = 0;
	} else {
		$right_type = 1;
	}
	if (array_key_exists("new_branch", $_POST) && ($_POST['new_branch'] != "")) {
		$branch = $_POST['new_branch'];

		$status = users_db_new_branch($role_id, $action_id, $branch, $right_type);
		if ($status == "error") {
			// обработка ошибки
			balo3_error("db error", true);
			exit;
		}

	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback.tpl"));

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
	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id'])) {
		$role_id = $_POST['role_id'];
	} else {
		$role_id = 0;
	}

	if ( array_key_exists("action_id", $_POST) && preg_match("/^\d+$/", $_POST['action_id'])) {
		$action_id = $_POST['action_id'];
	} else {
		$action_id = 0;
	}

	$smarty->assign_by_ref("role_id", $role_id);
	$smarty->assign_by_ref("action_id", $action_id);

	// создадим массив новых прав роли на данное действие
	$new_rights = array();
	foreach ($_POST as $k=>$v) {
		if (preg_match("/^br_(\d+)$/", $k, $matches)) {
			$num = $matches[1];
			// удалено?
			if (array_key_exists('del_' . $num, $_POST) && ($_POST['del_' . $num] == "on")) continue;
			// путь
			if ($v == "") continue;
			$new_rights[$num]['affected_branch'] = $v;
			// флаг
			if (array_key_exists('type_' . $num, $_POST) && ($_POST['type_' . $num] == "on")) {
				$new_rights[$num]['right_type'] = 0;
			} else {
				$new_rights[$num]['right_type'] = 1;
			}
		}
	}

	// теперь вызываем функцию обновления прав роли
	$status = db_users_update_role_actions($role_id, $action_id, $new_rights);
	if ($status == "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// если все хорошо, то смотрим, добавилась ли новая ветка
	if (array_key_exists("type_new_branch", $_POST) && ($_POST['type_new_branch'] == "on")) {
		$right_type = 0;
	} else {
		$right_type = 1;
	}
	if (array_key_exists("new_branch", $_POST) && ($_POST['new_branch'] != "")) {
		$branch = $_POST['new_branch'];

		$status = db_users_new_branch($role_id, $action_id, $branch, $right_type);
		if ($status == "error") {
			echo $_ERRORS['DB_ERROR'];
			exit;
		}

	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>