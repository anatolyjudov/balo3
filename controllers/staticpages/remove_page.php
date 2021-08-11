<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// принимаем параметр
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/^\d+$/", $_POST['node'])) ) {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}

	// информация о вершине
	$old_node_info = manuka_db_get_node_info_by_id($_POST['node']);
	if ($old_node_info == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($old_node_info == "notfound") {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}
	$smarty->assign_by_ref("node_info", $old_node_info);

	// проверка прав
	if (!users_db_check_rights(users_get_user_id(), 'MODIFY_HTML', $old_node_info['NODE_PATH'])) {
		// обработка ошибки
		balo3_error403();
		exit;
	}

	$page_id = false;
	foreach($old_node_info['controllers'] as $controller) {
		if ( ("staticpages" === $controller['CONTROLLER_FAMILY']) && ("page" === $controller['CONTROLLER'])) {
			$page_id = $controller['CONTROLLER_ARGS'];
		}
		$controller_info = $controller;
	}
	if ($page_id === false) {
		// обработка ошибки
		balo3_error("node has not staticpage controller", true);
		exit;
	}
	$smarty->assign_by_ref("controller_info", $controller_info);

	// информация о странице
	$old_page_info = staticpages_db_get_page_by_id($page_id);
	if ("error" === $page_id) {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ("notfound" === $page_id) {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}
	$smarty->assign("page_info", $old_page_info);

	// удаляем
	$status = staticpages_db_remove_page($old_node_info['NODE_ID'], $page_id);
	if ("error" === $status) {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}


	balo3_controller_output($smarty->fetch("$templates_path/staticpages/remove.tpl"));

} while (false);

?>

<?
/*
include($file_path."/includes/html/html_db.php");
include($file_path."/includes/html/html_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_HTML_ERRORS;

	// принимаем параметр
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/^\d+$/", $_POST['node'])) ) {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// проверяем
	$html_node_info = db_get_node_by_id($_POST['node']);
	if ($html_node_info == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($html_node_info == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}
	$smarty->assign("node_info", $html_node_info);

	if (!db_check_rights(get_user_id(), 'DELETE_HTML', $html_node_info['M_PATH'])) {
		// обработка ошибки
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// удаляем html-страницу
	$status = db_html_remove_html_node($_POST['node']);
	if ($status == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}
	if ($status == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($status == "nothtml") {
		// обработка ошибки
		echo $_HTML_ERRORS['NON_HTML_IN_TREE'];
		exit;
	}

	// удаляем метаинформацию
	if ($meta_component) {
		include($file_path."/includes/meta/meta_config.php");
		include($file_path."/includes/meta/meta_db.php");
	
		$uri = $html_node_info['M_PATH'];
		$status = meta_db_delete_metainfo_branch($uri);
		if ($status === "error") {
			echo $meta_error;
			exit;
		}
	}

	// выводим диалог
	out_main($smarty->fetch("html/remove.tpl"));

*/
?>