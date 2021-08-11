<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// принимаем параметр
	if ( (!array_key_exists("node", $_GET)) || (!preg_match("/^\d+$/", $_GET['node'])) ) {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}

	// информация о вершине
	$node_info = manuka_db_get_node_info_by_id($_GET['node']);
	if ($node_info == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($node_info == "notfound") {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}
	$smarty->assign_by_ref("node_info", $node_info);

	// проверка прав
	if (!users_db_check_rights(users_get_user_id(), 'MODIFY_HTML', $node_info['NODE_PATH'])) {
		// обработка ошибки
		balo3_error403();
		exit;
	}

	$page_id = false;
	foreach($node_info['controllers'] as $controller) {
		if ( ("staticpages" === $controller['CONTROLLER_FAMILY']) && ("page" === $controller['CONTROLLER'])) {
			$page_id = $controller['CONTROLLER_ARGS'];
		}
	}
	if ($page_id === false) {
		// обработка ошибки
		balo3_error("node has not staticpage controller", true);
		exit;
	}

	// информация о странице
	$page_info = staticpages_db_get_page_by_id($page_id);
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
	$smarty->assign("page_info", $page_info);

	// метаинформация страниц
	$meta_info = meta_db_get_metainfo_branch($node_info['NODE_PATH']);
	if ($meta_info == "error") {
		balo3_error("db error getting meta info", true);
		exit;
	}
	if ("notfound" === $meta_info) {
		$meta_info = array();
	}

	$meta_info = current($meta_info);

	$smarty->assign_by_ref("meta_info", $meta_info);

	$meta_info["uri"] = $node_info['NODE_PATH'];

	//show_ar($meta_info);

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/edit.tpl"));

} while (false);

?>



<?
/*
include($file_path."/includes/html/html_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;



	// принимаем параметр
	if ( (!array_key_exists("node", $_GET)) || (!preg_match("/^\d+$/", $_GET['node'])) ) {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// параметр кладем в смарти
	$html_node_info = db_get_node_by_id($_GET['node']);
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

	if (!db_check_rights(get_user_id(), 'MODIFY_HTML', $html_node_info['M_PATH'])) {
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

	if ($farch_component) {
		if ($html_info["HTML_IMAGE_FOTO_ID"] != "") {
			$html_image_info = far_db_get_fotos_info($html_info["HTML_IMAGE_FOTO_ID"]);
			if ($html_image_info === "error") {
				echo $_ERRORS['DB_ERROR'];
				exit;
			}
			$html_info['html_image'] = $html_image_info[key($html_image_info)];
		}
	}

	//show_ar($html_info);

	// если компонент мета подключен, надо вывести поля для редактирования метаинформации
	global $meta_component;
	if ($meta_component) {
		include($file_path."/includes/meta/meta_config.php");
		include($file_path."/includes/meta/meta_db.php");
		$smarty->assign("meta", true);

		$path = $html_node_info["M_PATH"];
		$metainfo = meta_db_get_metainfo_branch($path);
		reset($metainfo);

		$meta_info = array();
		$smarty->assign_by_ref("meta_info", $meta_info);

		$meta_info["title"] = $metainfo[key($metainfo)]["TITLE"];
		$meta_info["link"] = $metainfo[key($metainfo)]["INNER_TITLE"];
		$meta_info["keywords"] = $metainfo[key($metainfo)]["KEYWORDS"];
		$meta_info["description"] = $metainfo[key($metainfo)]["DESCRIPTION"];
		$meta_info["uri"] = $path;

		if ($farch_component) {
			$meta_info["head_image_foto_id"] = $metainfo[key($metainfo)]["HEAD_IMAGE_FOTO_ID"];
			if ($meta_info["head_image_foto_id"] != "") {
				$head_image_info = far_db_get_fotos_info($meta_info["head_image_foto_id"]);
				if ($head_image_info === "error") {
					echo $_ERRORS['DB_ERROR'];
					exit;
				}
				//echo key($head_image_info);
				//show_ar($head_image_info);
				$meta_info['head_image'] = $head_image_info[key($head_image_info)];
			}
		}
		//show_ar($meta_info);
	}

	// выводим диалог
	out_main($smarty->fetch("html/edit.tpl"));
*/
?>