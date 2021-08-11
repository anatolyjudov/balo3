<?php

require_once("$entrypoint_path/components/files/files_init.php");

do {

	# проверим входные данные
	if (array_key_exists("fullname", $_GET) && ($_GET['fullname']!="")) {
		$fullname = $_GET['fullname'];
	} else {
		balo3_error($_FILES_ERRORS['FILENAME_NOT_DEFINED'], true);
		exit;
	}

	$smarty->assign_by_ref("fullname", $fullname);

	// выделим путь
	preg_match("/^(.*\/)[^\/]*$/", $fullname, $matches);

	// проверка прав
	/*
	if ( !db_check_rights(get_user_id(), 'DELETE_FILES', $matches[1]) ) {
		balo3_error($_ERRORS['ACCESS_DENIED'], true);
		exit;
	}
	*/

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/files/delete_file.tpl"));

} while (false);

?>



<?php
/*
include($file_path."/includes/files/files_texts.php");

global $smarty;
global $params;
global $node_info;
global $files_path;

global $_ERRORS;

# проверим входные данные
if (array_key_exists("fullname", $_GET) && ($_GET['fullname']!="")) {
	$fullname = $_GET['fullname'];
} else {
	echo $_FILES_ERRORS['FILENAME_NOT_DEFINED'];
	exit;
}

$smarty->assign_by_ref("fullname", $fullname);

// выделим путь
preg_match("/^(.*\/)[^\/]*$/", $fullname, $matches);

// проверка прав
if ( !db_check_rights(get_user_id(), 'DELETE_FILES', $matches[1]) ) {
	echo $_ERRORS['ACCESS_DENIED'];
	exit;
}

// выдаем все в шаблон
$smarty->display("files/delete_file.tpl");
*/
?>