<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	$html_id = $controller_args;
	if (!is_numeric($html_id)) {
		balo3_error("bad html_id for page", true);
		exit;
	}

	// достанем из базы данных страницу
	$staticpage_info = staticpages_db_get_page_by_id($html_id);
	if ($staticpage_info == "error") {
		balo3_error("db error getting static page", true);
		exit;
	}
	if ($staticpage_info == "notfound") {
		balo3_error("static page not found", true);
		exit;
	}

	// достанем права пользователя
	$staticpages_user_rights = users_db_get_node_multiple_rights(users_get_user_id(), "MODIFY_HTML,ADD_HTML,DELETE_HTML,CHANGE_METAINFO", $balo3_node_info['NODE_PATH']);
	if ($staticpages_user_rights == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	$this_user_html_actions = array();
	foreach ($staticpages_user_rights as $action=>$branches) {
		// возьмем последнее значение
		// echo array_pop($branches);
		if (array_pop($branches) == 1) {
			$this_user_html_actions[$action] = true;
		}
	}
	// еще одна проверочка
	if (users_db_check_rights(users_get_user_id(), 'ACCESS_NODE', $staticpages_admin_branch)) {
		$this_user_html_actions['access_html_tree'] = true;
	}
	$smarty->assign_by_ref("this_user_html_actions", $this_user_html_actions);

	$smarty->assign_by_ref("node_id", $balo3_node_info['NODE_ID']);
	$smarty->assign_by_ref("node_path", $balo3_node_info['NODE_PATH']);
	$smarty->assign_by_ref("staticpage_info", $staticpage_info);
	$smarty->assign_by_ref("staticpages_admin_branch", $staticpages_admin_branch);

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/page.tpl"));

} while (false);

?>

<?
/*

include($file_path."/includes/html/html_config.php");
include($file_path."/includes/html/html_db.php");
include($file_path."/includes/html/html_texts.php");

global $smarty;
global $params;
global $node_info;
global $html_admin_branch;

	$node_uri = "/".trim($params['strpath'], "/")."/";
	if ($node_uri == "//") {
		$node_uri = "/";
	}

	// достанем из базы данных страницу
	$page_info = db_html_get_page_by_id($node_info['current']['M_INSTANCE']);
	if ($page_info == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($page_info == "notfound") {
		out_main($smarty->fetch("html/notfound.tpl"));
	}

	// если есть компонент галереи и есть привязанная картинка - достанем информацию о ней
	if ( $farch_component && isset($page_info['HTML_IMAGE_FOTO_ID']) && ($page_info['HTML_IMAGE_FOTO_ID'] != '') ) {
		$page_info['html_image'] = far_db_get_foto_info($page_info['HTML_IMAGE_FOTO_ID']);
		if ($page_info['html_image'] == 'error') {
			out_main($_ERRORS['DB_ERROR']);
		}
		if ($page_info['html_image'] == 'notfound') {
			$page_info['html_image'] = array();
		}
	}

	// определим id страницы в мануке
	if (isset($node_info['current']['level'])) {
		$aindex = 'M_ID_'.$node_info['current']['level'];
	} else {
		$aindex = 'M_ID';
	}
	$m_id = $node_info['current'][$aindex];

	// show_ar($page_info);
	// show_ar($node_info);

/*
	if (count($node_info['nodes']) < 2) {
		// достанем вложенные html-вершины
		$html_subtree = db_html_get_tree_list($m_id, false);
		if ($html_subtree == "error") {
			echo $_ERRORS['DB_ERROR'];
			exit;
		}

		// уберем текущую вершину из этой выдачи
		unset($html_subtree[$m_id]);
	}

	if (count($node_info['nodes']) >= 2) {
		$nodes = array_values($node_info['nodes']);
		$parent_m_id = $nodes[count($nodes)-2]["M_ID_".($node_info['current']['level']-1)];
		// достанем соседние html-вершины
		$html_subtree = db_html_get_tree_list($parent_m_id, false);
		if ($html_subtree == "error") {
			echo $_ERRORS['DB_ERROR'];
			exit;
		}

		// уберем текущую вершину из этой выдачи
		unset($html_subtree[$m_id]);

		// уберем родительскую вершину из этой выдачи
		unset($html_subtree[$parent_m_id]);
	}

	// получим фотки вложенных вершин
	if ($farch_component && (count($html_subtree) > 0) ) {

		$fotos_ids = array();
		foreach($html_subtree as $tmp_m_id => $tmp_node_info) {
			if (isset($tmp_node_info['HTML_IMAGE_FOTO_ID']) && ($tmp_node_info['HTML_IMAGE_FOTO_ID'] != '')) {
				$fotos_ids[$tmp_node_info['HTML_IMAGE_FOTO_ID']] = $tmp_m_id;
			}
		}

		if (count($fotos_ids) > 0) {

			$fotos_list = far_db_get_fotos_info(array_keys($fotos_ids));
			if ($fotos_list === 'error') {
				return $_ERRORS['DB_ERROR'];
			}

			foreach($fotos_list as $foto_id => $foto_info) {
				if (isset($fotos_ids[$foto_id])) {
					$html_subtree[$fotos_ids[$foto_id]]['html_image'] = $foto_info;
				}
			}

		}

	}

	//show_ar($html_subtree);
	$smarty->assign_by_ref("html_subtree", $html_subtree);
*/
/*
	// достанем права пользователя
	$user_rights = db_get_node_multiple_rights(get_user_id(), "MODIFY_HTML,ADD_HTML,DELETE_HTML,CHANGE_METAINFO", $node_uri);
	if ($user_rights == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$this_user_html_actions = array();
	foreach ($user_rights as $action=>$branches) {
		// возьмем последнее значение
		// echo array_pop($branches);
		if (array_pop($branches) == 1) {
			$this_user_html_actions[$action] = true;
		}
	}
	// еще одна проверочка
	if (db_check_rights(get_user_id(), 'ACCESS_NODE', $html_admin_branch)) {
		$this_user_html_actions['access_html_tree'] = true;
	}
	$smarty->assign_by_ref("this_user_html_actions", $this_user_html_actions);



	$smarty->assign_by_ref("page_info", $page_info);
	$smarty->assign("html_content", $page_info['HTML_CONTENT']);
	$smarty->assign("html_title", $page_info['HTML_PAGE_TITLE']);
	$smarty->assign_by_ref("m_id", $m_id);
	$smarty->assign_by_ref("node_uri", $node_uri);
	$smarty->assign_by_ref("html_admin_branch", $html_admin_branch);

	out_main($smarty->fetch("html/html.tpl"));
*/
?>