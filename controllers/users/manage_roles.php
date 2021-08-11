<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// берем из базы все что нужно
	$roles_list = users_db_get_roles();
	if ($roles_list == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($roles_list == "notfound") {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}

	// show_ar($roles_list);
	// полученный массив кладем в смарти
	$smarty->assign_by_ref("roles_list", $roles_list);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/users/manage_roles.tpl"));

} while (false);

?>



<?php
/*
include($file_path."/includes/users/users_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	// берем из базы все что нужно
	$roles_list = db_users_get_roles($from_node);
	if ($roles_list == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($roles_list == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	//show_ar($roles_list);
	// полученный массив кладем в смарти
	$smarty->assign_by_ref("roles_list", $roles_list);

	// выдаем шаблон
	out_main($smarty->fetch("users/manage_roles.tpl"));
*/
?>