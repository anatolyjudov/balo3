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

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/delete.tpl"));

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

	// параметр кладем в смарти
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

	// берем из базы кусок дерева под выбранной вершиной
	$manuka_nodes = db_html_get_tree($html_node_info['M_ID']);
	if ($manuka_nodes == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($manuka_nodes == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

//	show_ar($manuka_nodes);
	
	foreach ($manuka_nodes as $m_id=>$node) {
		if (($node['M_EXECUTIVE']!="html.php") || ($node['M_COMPONENT']!="html") ) {
			// обработка ошибки
			echo $_HTML_ERRORS['NON_HTML_IN_TREE'];
			exit;
		}
	}
	unset( $manuka_nodes[$html_node_info['M_ID']] );
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	// достаем html-информацию вершины
	$html_info = db_html_get_page_by_id($html_node_info['M_INSTANCE']);
	if ($html_info == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("html_info", $html_info);

	// выводим диалог
	out_main($smarty->fetch("html/delete.tpl"));

*/
?>