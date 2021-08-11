<?php

require_once("$entrypoint_path/components/files/files_init.php");

do {

	# проверим входные данные (путь и фильтры)
	if (array_key_exists("path", $_GET) && ($_GET['path']!="")) {
		$path = $_GET['path'];
	} else {
		$path = "/";
	}

	if (substr($path, -1)!= "/") {
		$path .= "/";
	}

	if (substr($path, 0, 1)!= "/") {
		$path = "/" . $path;
	}

	if (preg_match("/\/\.\.\//", "/" . $path)) {
		balo3_error($_FILES_ERRORS['BAD_PATH'], true);
		exit;
	}

	$smarty->assign_by_ref("path", $path);

	if ($path != "/") {
		$parent = preg_replace("/^(.*)\/([^\/]*)\/$/", "\\1/", $path);
		if ($parent == "") {
			$parent = "/";
		}
	} else {
		$parent = "";
	}

	$smarty->assign_by_ref("parent", $parent);

	if (array_key_exists("filters", $_GET) && ($_GET['filters']!="")) {
		$filters = explode(",", $_GET['filters']);
	} else {
		$filters = array();
	}

	$smarty->assign_by_ref("filters", $filters);

	$files_info = array();

	// проверим наличие запрошенной директории
	if ($dir = @opendir($files_path . "/" . $path)) {

		// берем список файлов в ней
		while (false !== ($file = readdir($dir))) { 
			if ( ($file == "..") || ($file == ".") ) continue;
			preg_match("/\.(.*)$/", $file, $matches);
			$type = filetype($files_path . "/" . $path . "/" . $file);
			if ( (($type == "dir") || ($type == "file")) && ( (!isset($matches[1])) || (!array_key_exists($matches[1], $filters))) ) {
				$files_info[$file]['filename'] = $file;
				$files_info[$file]['type'] = $type;
				$files_info[$file]['size'] = filesize($files_path . "/" . $path . "/" . $file);
				$files_info[$file]['last_modify'] = filemtime($files_path . "/" . $path . "/" . $file);
				if (isset($matches[1])) {
					$files_info[$file]['ext'] = $matches[1];
				}
			}
		}
		closedir($dir);
		// применяем фильтры

	} else {
		$files_info = array();
	}

	sort($files_info);
	reset($files_info);

	$smarty->assign_by_ref("files_info", $files_info);

	if (isset($_GET['callback_func'])) {
		$callback_func = $_GET['callback_func'];
	} else {
		$callback_func = "";
	}
	$smarty->assign_by_ref("callback_func", $callback_func);

	// выдаем все в шаблон
	if (array_key_exists("popup", $_GET) && ($_GET['popup'] == "on")) {
		$smarty->display("files/files_popup.tpl");
		exit;
	} else {
		balo3_controller_output($smarty->fetch("files/files.tpl"));
		break;
	}

} while (false);

?>



<?php
/*
# files.php
# функция компонента files, выдает список файлов по заданному пути
# по умолчанию - корневая директория
# для доступа используется правило ACCESS_NODE

include($file_path."/includes/files/files_texts.php");

global $smarty;
global $params;
global $node_info;
global $files_path;

global $_ERRORS;
/*
# проверим входные данные (путь и фильтры)
if (array_key_exists("path", $_GET) && ($_GET['path']!="")) {
	$path = $_GET['path'];
} else {
	$path = "/";
}

if (substr($path, -1)!= "/") {
	$path .= "/";
}

if (substr($path, 0, 1)!= "/") {
	$path = "/" . $path;
}

if (preg_match("/\/\.\.\//", "/" . $path)) {
	echo $_FILES_ERRORS['BAD_PATH'];
	exit;
}

$smarty->assign_by_ref("path", $path);

if ($path != "/") {
	$parent = preg_replace("/^(.*)\/([^\/]*)\/$/", "\\1/", $path);
	if ($parent == "") {
		$parent = "/";
	}
} else {
	$parent = "";
}

$smarty->assign_by_ref("parent", $parent);

if (array_key_exists("filters", $_GET) && ($_GET['filters']!="")) {
	$filters = explode(",", $_GET['filters']);
} else {
	$filters = array();
}

$smarty->assign_by_ref("filters", $filters);

$files_info = array();

// проверим наличие запрошенной директории
if ($dir = @opendir($files_path . "/" . $path)) {

	// берем список файлов в ней
	while (false !== ($file = readdir($dir))) { 
		if ( ($file == "..") || ($file == ".") ) continue;
		preg_match("/\.(.*)$/", $file, $matches);
		$type = filetype($files_path . "/" . $path . "/" . $file);
		if ( (($type == "dir") || ($type == "file")) && (!array_key_exists($matches[1], $filters)) ) {
			$files_info[$file]['filename'] = $file;
			$files_info[$file]['type'] = $type;
			$files_info[$file]['size'] = filesize($files_path . "/" . $path . "/" . $file);
			$files_info[$file]['last_modify'] = filemtime($files_path . "/" . $path . "/" . $file);
			$files_info[$file]['ext'] = $matches[1];
		}
	}
	closedir($dir);
	// применяем фильтры

} else {
	$files_info = array();
}

sort($files_info);
reset($files_info);

$smarty->assign_by_ref("files_info", $files_info);

if (isset($_GET['callback_func'])) {
	$callback_func = $_GET['callback_func'];
} else {
	$callback_func = "";
}
$smarty->assign_by_ref("callback_func", $callback_func);

// выдаем все в шаблон
if (array_key_exists("popup", $_GET) && ($_GET['popup'] == "on")) {
	$smarty->display("files/files_popup.tpl");
} else {
	out_main($smarty->fetch("files/files.tpl"));
}
*/
?>