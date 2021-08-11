<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// принимаем опциональный параметр - верхушку дерева, откуда рисовать
	if (array_key_exists("node", $_GET) && (preg_match("/\d+/", $_GET['node']))) {
		$from_node = $_GET['node'];
	} else {
		$from_node = "";
	}

	if (isset($_GET['show_admin']) && ($_GET['show_admin'] =="on")) {
		$show_all = true;
	} else {
		$show_all = false;
	}
	$smarty->assign("show_admin", $show_all);

	if (isset($_GET['not_htmls']) && ($_GET['not_htmls'] =="on")) {
		$not_htmls = true;
	} else {
		$not_htmls = false;
	}
	$smarty->assign_by_ref("not_htmls", $not_htmls);

	if (isset($_GET['show_full_info']) && ($_GET['show_full_info'] =="on")) {
		$show_full_info = true;
	} else {
		$show_full_info = false;
	}
	$smarty->assign_by_ref("show_full_info", $show_full_info);

	if (isset($_GET['path_filter'])) {
		$path_filter = $_GET['path_filter'];
	} else {
		$path_filter = "";
	}
	$smarty->assign("path_filter", $path_filter);

	// берем из базы все что нужно
	$manuka_nodes = staticpages_db_get_tree($from_node, $show_all, !$not_htmls, $path_filter);
	if ($manuka_nodes == "error") {
		// обработка ошибки
		balo3_error("db error getting pages tree", true);
		exit;
	}
	if ($manuka_nodes == "notfound") {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}

	// достанем права пользователя
	$user_rights = users_db_get_multiple_rights(users_get_user_id(), "ACCESS_NODE,MODIFY_HTML,ADD_HTML,DELETE_HTML");
	if ($user_rights == "error") {
		// обработка ошибки
		balo3_error("db error getting user rights", true);
		exit;
	}

	// для каждой вершины дерева определим права
	foreach ($manuka_nodes as $node_id=>$node_info) {

		// для каждой вершины перебираем все действия
		foreach ($user_rights as $action_name=>$branches_info) {
			$manuka_nodes[$node_id]['user_rights'][$action_name] = array();
			// для каждого действия смотрим информацию
			$nearest_parent = "/";
			$nearest_parent_state = 0;
			foreach ($branches_info as $branch=>$right_state) {
				// если это один из предков, и он больше текущего - меняем state
				if ( (strpos($node_info['NODE_PATH'], $branch) === 0) && (strlen($branch) >= strlen($nearest_parent)) ) {
					$nearest_parent_state = $right_state;
					$nearest_parent = $branch;
				}
			}
			$manuka_nodes[$node_id]['user_rights'][$action_name] = $nearest_parent_state;
		}
		/*
		$manuka_nodes[$node_id]['user_rights']['ACCESS_NODE'] = 1;
		$manuka_nodes[$node_id]['user_rights']['ADD_HTML'] = 1;
		$manuka_nodes[$node_id]['user_rights']['MODIFY_HTML'] = 1;
		$manuka_nodes[$node_id]['user_rights']['DELETE_HTML'] = 1;
		*/
	}

	// полученный массив кладем в смарти
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/tree.tpl"));

} while (false);

?>


<?
/*
include($file_path."/includes/html/html_config.php");
include($file_path."/includes/html/html_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

//show_ar($node_info);
// тут мы будем рисовать дерево страниц

	// принимаем опциональный параметр - верхушку дерева, откуда рисовать
	if (array_key_exists("node", $_GET) && (preg_match("/\d+/", $_GET['node']))) {
		$from_node = $_GET['node'];
	} else {
		$from_node = "";
	}

	if (isset($_GET['show_admin']) && ($_GET['show_admin'] =="on")) {
		$show_all = true;
	} else {
		$show_all = false;
	}
	$smarty->assign("show_admin", $show_all);

	if (isset($_GET['not_htmls']) && ($_GET['not_htmls'] =="on")) {
		$not_htmls = true;
	} else {
		$not_htmls = false;
	}
	$smarty->assign_by_ref("not_htmls", $not_htmls);

	if (isset($_GET['show_full_info']) && ($_GET['show_full_info'] =="on")) {
		$show_full_info = true;
	} else {
		$show_full_info = false;
	}
	$smarty->assign_by_ref("show_full_info", $show_full_info);

	if (isset($_GET['path_filter'])) {
		$path_filter = $_GET['path_filter'];
	} else {
		$path_filter = "";
	}
	$smarty->assign("path_filter", $path_filter);

	// берем из базы все что нужно
	$manuka_nodes = db_html_get_tree($from_node, $show_all, !$not_htmls, $path_filter);
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

	// достанем права пользователя
	$user_rights = db_get_multiple_rights(get_user_id(), "ACCESS_NODE,MODIFY_HTML,ADD_HTML,DELETE_HTML");
	if ($user_rights == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

//	show_ar($user_rights);
//	show_ar($manuka_nodes);

	// для каждой вершины дерева определим права
	foreach ($manuka_nodes as $m_id=>$node) {
		// для каждой вершины перебираем все действия
		foreach ($user_rights as $action_name=>$branches_info) {
			$manuka_nodes[$m_id]['user_rights'][$action_name] = array();
			// для каждого действия смотрим информацию
			$nearest_parent = "/";
			$nearest_parent_state = 0;
			foreach ($branches_info as $branch=>$right_state) {
				// если это один из предков, и он больше текущего - меняем state
				if ( (strpos($node['M_PATH'], $branch) === 0) && (strlen($branch) >= strlen($nearest_parent)) ) {
					$nearest_parent_state = $right_state;
					$nearest_parent = $branch;
				}
			}
			$manuka_nodes[$m_id]['user_rights'][$action_name] = $nearest_parent_state;
		}
	}




	// полученный массив кладем в смарти
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	// выдаем шаблон
	out_main($smarty->fetch("html/tree.tpl"));
*/
?>