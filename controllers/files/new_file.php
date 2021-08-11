<?php

require_once("$entrypoint_path/components/files/files_init.php");

do {


	# проверим входные данные
	if (array_key_exists("path", $_GET) && ($_GET['path']!="")) {
		$path = $_GET['path'];
	} else {
		$path = "/";
	}

	if (substr($path, -1)!= "/") {
		$path .= "/";
	}

	// проверка прав
	/*
	if ( !db_check_rights(get_user_id(), 'UPLOAD_FILES', $path) ) {
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}
	*/

	$smarty->assign_by_ref("path", $path);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/files/new_file.tpl"));

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
if (array_key_exists("path", $_GET) && ($_GET['path']!="")) {
	$path = $_GET['path'];
} else {
	$path = "/";
}

if (substr($path, -1)!= "/") {
	$path .= "/";
}

// проверка прав
if ( !db_check_rights(get_user_id(), 'UPLOAD_FILES', $path) ) {
	echo $_ERRORS['ACCESS_DENIED'];
	exit;
}

$smarty->assign_by_ref("path", $path);

// выдаем все в шаблон
$smarty->display("files/new_file.tpl");
*/
?>