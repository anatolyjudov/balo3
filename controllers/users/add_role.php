<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {
	// принимаем параметр
	if ( array_key_exists("role_name", $_POST) && ($_POST['role_name']!="") ) {
	} else {
		// обработка ошибки
		balo3_error($_USERS_ERRORS['EMPTY_ROLE_NAME'], true);
		exit;
	}

	// добавляем в базу
	$status = users_db_add_role($_POST['role_name']);
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
	if ( array_key_exists("role_name", $_POST) && ($_POST['role_name']!="") ) {
	} else {
		// обработка ошибки
		echo $_USERS_ERRORS['EMPTY_ROLE_NAME'];
		exit;
	}

	// добавляем в базу
	$status = db_users_add_role($_POST['role_name']);
	if ($status == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback.tpl"));
*/
?>