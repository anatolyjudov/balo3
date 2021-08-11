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
	}
	if ($page_id === false) {
		// обработка ошибки
		balo3_error("node has not staticpage controller", true);
		exit;
	}

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

	// параметры html-страницы
	$page_info['html_content'] = $_POST['html_content'];
	$page_info['html_page_title'] = $_POST['html_page_title'];
	if (isset($_POST['plain_html_edit']) && ($_POST['plain_html_edit'] == "on")) {
		$page_info['plain_html_edit'] = 1;
	} else {
		$page_info['plain_html_edit'] = 0;
	}

	// вызываем функцию, которая апдейтит html-страницу
	if ($staticpages_farch_component) {
		if (preg_match("/^\d+$/", $_POST['html_image_foto_id'])) {
			$html_image_foto_id = $_POST['html_image_foto_id'];
		} else {
			$html_image_foto_id = "";
		}
		$status = staticpages_db_update_page($old_page_info['HTML_ID'], $page_info, $html_image_foto_id);
	} else {
		$status = staticpages_db_update_page($old_page_info['HTML_ID'], $page_info);
	}
	if ($status == "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($status == "notfound") {
		// обработка ошибки
		balo3_error("no such node", true);
		exit;
	}


	//  взаимодействие с компонентом Meta
	if ($meta_component) {
		$uri = $_POST["meta_uri"]."/";
		$title = $_POST["meta_title"];
		$link = $_POST["meta_link"];
		$keywords = $_POST["meta_keywords"];
		$description = $_POST["meta_description"];

		if ($staticpages_farch_component) {
			if (preg_match("/^\d+$/", $_POST['meta_head_image_foto_id'])) {
				$head_image_foto_id = $_POST['meta_head_image_foto_id'];
			} else {
				$head_image_foto_id = "";
			}
			$status = meta_db_set_metainfo($uri, $title, $link, $keywords, $description, '', '', $head_image_foto_id);
		} else {
			$status = meta_db_set_metainfo($uri, $title, $link, $keywords, $description);
		}

		if ($status === "error") {
			// обработка ошибки
			balo3_error("db error", true);
			exit;
		}
	}



	$smarty->assign_by_ref("new_node_info", $new_node_info);
	$smarty->assign_by_ref("html_node_info", $html_node_info);
	$smarty->assign_by_ref("new_html_info", $new_html_info);
	$smarty->assign_by_ref("html_admin_branch", $html_admin_branch);

	balo3_controller_output($smarty->fetch("$templates_path/staticpages/modify.tpl"));

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

global $_ERRORS;

global $meta_component;
	if ($meta_component) {
		include($file_path."/includes/meta/meta_config.php");
		include($file_path."/includes/meta/meta_db.php");
	}


	// принимаем параметр
	if ( (!array_key_exists("m_id", $_POST)) || (!preg_match("/^\d+$/", $_POST['m_id'])) ) {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// параметр кладем в смарти
	$html_node_info = db_get_node_by_id($_POST['m_id']);
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

	if (!db_check_rights(get_user_id(), 'MODIFY_HTML', $html_node_info['M_PATH'])) {
		// обработка ошибки
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// параметры вершины
	if (array_key_exists("m_dirname", $_POST) && (preg_match("/^[\w\-_]*$/", $_POST['m_dirname']))) {
		$new_node_info['m_dirname'] = $_POST['m_dirname'];
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}
	if (array_key_exists("m_component", $_POST) && ($_POST['m_component'] != "")) {
		$new_node_info['m_component'] = $_POST['m_component'];
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}
	if (array_key_exists("m_instance", $_POST) && (preg_match("/^\d+$/", $_POST['m_instance']))) {
		$new_node_info['m_instance'] = $_POST['m_instance'];
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}
	if (array_key_exists("m_executive", $_POST) && ($_POST['m_executive'] != "")) {
		$new_node_info['m_executive'] = $_POST['m_executive'];
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}
	if (array_key_exists("m_template", $_POST) && ($_POST['m_template'] != "")) {
		$new_node_info['m_template'] = $_POST['m_template'];
	} else {
		// обработка ошибки
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// вызываем функцию, которая апдейтит вершину
	$status = db_update_node($_POST['m_id'], $new_node_info);
	if ($status == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($status == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// параметры html-страницы
	$new_html_info['html_content'] = $_POST['html_content'];
	$new_html_info['html_page_title'] = $_POST['html_page_title'];

	// вызываем функцию, которая апдейтит html-страницу
	if ($staticpages_farch_component) {
		if (preg_match("/^\d+$/", $_POST['html_image_foto_id'])) {
			$html_image_foto_id = $_POST['html_image_foto_id'];
		} else {
			$html_image_foto_id = "";
		}
		$status = db_html_update_page($new_node_info['m_instance'], $new_html_info, $html_image_foto_id);
	} else {
		$status = db_html_update_page($new_node_info['m_instance'], $new_html_info);
	}
	if ($status == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($status == "notfound") {
		// обработка ошибки
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}



	//  взаимодействие с компонентом Meta
	if ($meta_component) {
		$uri = $_POST["meta_uri"]."/";
		$title = $_POST["meta_title"];
		$link = $_POST["meta_link"];
		$keywords = $_POST["meta_keywords"];
		$description = $_POST["meta_description"];

		if ($staticpages_farch_component) {
			if (preg_match("/^\d+$/", $_POST['meta_head_image_foto_id'])) {
				$head_image_foto_id = $_POST['meta_head_image_foto_id'];
			} else {
				$head_image_foto_id = "";
			}
			$status = meta_db_set_metainfo($uri, $title, $link, $keywords, $description, '', '', $head_image_foto_id);
		} else {
			$status = meta_db_set_metainfo($uri, $title, $link, $keywords, $description);
		}

		if ($status === "error") {
			echo $meta_error;
			exit;
		}
	}

	$smarty->assign_by_ref("new_node_info", $new_node_info);
	$smarty->assign_by_ref("html_node_info", $html_node_info);
	$smarty->assign_by_ref("new_html_info", $new_html_info);
	$smarty->assign_by_ref("html_admin_branch", $html_admin_branch);
	
	// выводим диалог
	out_main($smarty->fetch("html/modify.tpl"));
*/
?>