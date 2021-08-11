<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// принимаем параметр
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/\d+/", $_POST['node'])) ) {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}

	// достанем информацию о родительской вершине
	$parent_node = manuka_db_get_node_info_by_id($_POST['node']);
	if("error" == $parent_node) {
		balo3_error("db error", true);
		exit;
	}
	if("notfound" == $parent_node) {
		balo3_error("no such node", true);
		exit;
	}

	if (!users_db_check_rights(users_get_user_id(), 'ADD_HTML', $parent_node['NODE_PATH'])) {
		// обработка ошибки
		balo3_error403();
		exit;
	}

	// параметр кладем в смарти
	$smarty->assign("parent_node", $parent_node['NODE_ID']);

	if (isset($_POST['show_admin']) && ($_POST['show_admin'] =="on")) {
		$show_all = true;
	} else {
		$show_all = false;
	}
	$smarty->assign("show_admin", $show_all);

	if (isset($_POST['not_htmls']) && ($_POST['not_htmls'] =="on")) {
		$not_htmls = true;
	} else {
		$not_htmls = false;
	}
	$smarty->assign_by_ref("not_htmls", $not_htmls);

	// берем из базы кусок дерева под выбранной вершиной
	$manuka_nodes = staticpages_db_get_tree($parent_node['NODE_PATH'], $show_all, !$not_htmls);
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

	$top_node = current($manuka_nodes);

//	show_ar($manuka_nodes);

	// полученный массив кладем в смарти
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/new.tpl"));

} while (false);

?>




<?
/*
include($file_path."/includes/html/html_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

// файл выводит диалог добавления вершины

	// принимаем параметр
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/\d+/", $_POST['node'])) ) {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// параметр кладем в смарти
	$smarty->assign("parent_node", $_POST['node']);

	if (isset($_POST['show_admin']) && ($_POST['show_admin'] =="on")) {
		$show_all = true;
	} else {
		$show_all = false;
	}
	$smarty->assign("show_admin", $show_all);

	if (isset($_POST['not_htmls']) && ($_POST['not_htmls'] =="on")) {
		$not_htmls = true;
	} else {
		$not_htmls = false;
	}
	$smarty->assign_by_ref("not_htmls", $not_htmls);

	// берем из базы кусок дерева под выбранной вершиной
	$manuka_nodes = db_html_get_tree($_POST['node'], $show_all, !$not_htmls);
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

	$top_node = current($manuka_nodes);

	//show_ar($top_node);

	if (!db_check_rights(get_user_id(), 'ADD_HTML', $top_node['M_PATH'])) {
		// обработка ошибки
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// полученный массив кладем в смарти
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	// выводим диалог
	out_main($smarty->fetch("html/new.tpl"));
*/
?>