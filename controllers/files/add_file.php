<?php

require_once("$entrypoint_path/components/files/files_init.php");

do {

	# проверим входные данные
	if (array_key_exists("path", $_POST) && ($_POST['path']!="")) {
		$path = $_POST['path'];
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

	//phpinfo();exit;
	//show_ar($_FILES);exit;

	// проверка на загрузку
	if ($_FILES['afile']['error'] != 0) {
		balo3_error($_FILES_ERRORS['FILE_NOT_UPLOADED'], true);
		exit;
	}

	// проверка на безопасность
	if (!is_uploaded_file($_FILES['afile']['tmp_name'])) {
		balo3_error($_FILES_ERRORS['BAD_FILE'], true);
		exit;
	}

	// проверяем путь
	$path_array = explode("/", rtrim($path, '/'));
	$path_checked = "";
	foreach($path_array as $k=>$v) {
		if ($v == "") continue;
		if (!is_dir($files_path . $path_checked . "/" . $v)) {
			if (!mkdir($files_path . $path_checked . "/" . $v, $files_dirs_new_mask)) {
				balo3_error($_FILES_ERRORS['CANNOT_CREATE_DIR'] . "[" . $files_path . $path_checked . "/" . $v . "]", true);
				exit;
			}
		}
		$path_checked .= "/" . $v;
	}

	// копируем
	move_uploaded_file($_FILES['afile']['tmp_name'], $files_path . $path . $_FILES['afile']['name']);

	@chmod($files_path . $path . $_FILES['afile']['name'], $files_new_mask);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/files/add_file.tpl"));

} while (false);

?>




<?php
/*
include($file_path."/includes/files/files_config.php");
include($file_path."/includes/files/files_texts.php");

global $smarty;
global $params;
global $node_info;
global $files_path;
global $files_new_mask, $files_dirs_new_mask;

global $_ERRORS;

# проверим входные данные
if (array_key_exists("path", $_POST) && ($_POST['path']!="")) {
	$path = $_POST['path'];
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

//phpinfo();exit;
//show_ar($_FILES);exit;

// проверка на загрузку
if ($_FILES['afile']['error'] != 0) {
	echo $_FILES_ERRORS['FILE_NOT_UPLOADED'];
	exit;
}

// проверка на безопасность
if (!is_uploaded_file($_FILES['afile']['tmp_name'])) {
	echo $_FILES_ERRORS['BAD_FILE'];
	exit;
}

// проверяем путь
$path_array = explode("/", rtrim($path, '/'));
$path_checked = "";
foreach($path_array as $k=>$v) {
	if ($v == "") continue;
	if (!is_dir($files_path . $path_checked . "/" . $v)) {
		if (!mkdir($files_path . $path_checked . "/" . $v, $files_dirs_new_mask)) {
			echo $_FILES_ERRORS['CANNOT_CREATE_DIR'] . "[" . $files_path . $path_checked . "/" . $v . "]";
			exit;
		}
	}
	$path_checked .= "/" . $v;
}

// копируем
move_uploaded_file($_FILES['afile']['tmp_name'], $files_path . $path . $_FILES['afile']['name']);

@chmod($files_path . $path . $_FILES['afile']['name'], $files_new_mask);

// выдаем все в шаблон
$smarty->display("files/add_file.tpl");
*/
?>