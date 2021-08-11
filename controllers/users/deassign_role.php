<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// принимаем параметр
	if ( array_key_exists("user_id", $_POST) && preg_match("/^\d+$/", $_POST['user_id']) ) {
	} else {
		// обработка ошибки
		balo3_error("bad parameter", true);
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// обработка ошибки
		balo3_error("bad parameter", true);
		exit;
	}

	// удаляем привязку
	$status = users_db_deassign_role($_POST['user_id'], $_POST['role_id']);
	if ($status == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}

	// выдаем шаблон
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

	// принимаем параметр
	if ( array_key_exists("user_id", $_POST) && preg_match("/^\d+$/", $_POST['user_id']) ) {
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	if ( array_key_exists("role_id", $_POST) && preg_match("/^\d+$/", $_POST['role_id']) ) {
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// удаляем привязку
	$status = db_users_deassign_role($_POST['user_id'], $_POST['role_id']);
	if ($status == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>