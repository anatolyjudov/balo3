<?php

require_once("$entrypoint_path/components/files/files_init.php");

do {

	# проверим входные данные
	if (array_key_exists("fullname", $_POST) && ($_POST['fullname']!="")) {
		$fullname = $_POST['fullname'];
	} else {
		echo $_FILES_ERRORS['FILENAME_NOT_DEFINED'];
		exit;
	}

	// выделим путь
	preg_match("/^(.*\/)[^\/]*$/", $fullname, $matches);

	// проверка прав
	/*
	if ( !db_check_rights(get_user_id(), 'DELETE_FILES', $matches[1]) ) {
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}
	*/

	if (!unlink($files_path . $fullname)) {
		echo $_FILES_ERRORS['CANNOT_DELETE_FILE'] . "[" . $files_path . $fullname ."]";
		exit;
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/files/remove_file.tpl"));

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
if (array_key_exists("fullname", $_POST) && ($_POST['fullname']!="")) {
	$fullname = $_POST['fullname'];
} else {
	echo $_FILES_ERRORS['FILENAME_NOT_DEFINED'];
	exit;
}

// выделим путь
preg_match("/^(.*\/)[^\/]*$/", $fullname, $matches);

// проверка прав
if ( !db_check_rights(get_user_id(), 'DELETE_FILES', $matches[1]) ) {
	echo $_ERRORS['ACCESS_DENIED'];
	exit;
}

if (!unlink($files_path . $fullname)) {
	echo $_FILES_ERRORS['CANNOT_DELETE_FILE'] . "[" . $files_path . $fullname ."]";
	exit;
}

// выдаем все в шаблон
$smarty->display("files/remove_file.tpl");
*/
?>