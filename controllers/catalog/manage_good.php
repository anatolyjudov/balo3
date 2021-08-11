<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$l = count(explode("/", trim($catalog_admin_uri, "/")));
	$section_id = $balo3_request_info['path'][$l+1];
	$good_id = $balo3_request_info['path'][$l+4];
	if (!is_numeric($good_id)) {
		balo3_error('bad parameter', true);
		exit;
	}
	$smarty->assign_by_ref("section_id", $section_id);
	$smarty->assign_by_ref("good_id", $good_id);

	$old_good_tags_list = catalog_db_get_good_tags($good_id);
	if ($old_good_tags_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	//show_ar($old_good_tags_list);

	$old_good_info = catalog_db_get_good_info($good_id);
	if ($old_good_info === 'error') {
		balo3_error("db error", true);
		exit;
	}

	// информация о дополнительных секциях
	$good_sections_list = catalog_db_get_good_additional_sections($good_id);
	if ($good_sections_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("good_sections_list", $good_sections_list);

	if (!isset($_POST['title'])) {

		// данные об авторе лота
		if ($authors_component) {

			$author_info = authors_db_get_authors_for_catalog_good($good_id);
			//show_ar($author_info);
			
			$authors_list = authors_db_get_authors_list ();
			//show_ar($authors_list);

			$smarty->assign_by_ref("author_info", $author_info);
			$smarty->assign_by_ref("authors_list", $authors_list);
		}

		$smarty->assign_by_ref("good_tags_list", $old_good_tags_list);
		$smarty->assign_by_ref("good_info", $old_good_info);

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good.tpl"));
		break;
	}

	// прием параметров
/*	$good_info = array(
		"GOOD_ID" => $good_id
		);
	
	// если несколько авторов 
	
	//show_ar($_POST);
	//show_ar(array_keys($_POST));
	foreach ((array_keys($_POST)) as $key) {
		//echo($key)."<br>";
		if ( ((substr($key, 0, 13)) == "author_choose") && ($_POST["$key"] == "on") ) {
			//echo"yes!!!"."<br>";
			$authors_ids[] = substr($key, 14);
		}

	 }
	 //show_ar($authors_ids);


	if (isset($authors_ids)) {
		$good_info['AUTHOR_ID'] = $authors_ids;
		//show_ar($good_info['AUTHOR_ID']);
	} else {
		$good_info['AUTHOR_ID'] = '';
	} */
	if (isset($_POST['section_id'])) {
		$good_info['SECTION_ID'] = $_POST['section_id'];
	} else {
		$good_info['SECTION_ID'] = '';
	}
	if (isset($_POST['title'])) {
		$good_info['TITLE'] = $_POST['title'];
	} else {
		$good_info['TITLE'] = '';
	}
	if (isset($_POST['short_text'])) {
		$good_info['SHORT_TEXT'] = $_POST['short_text'];
	} else {
		$good_info['SHORT_TEXT'] = '';
	}
	if (isset($_POST['description'])) {
		$good_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$good_info['DESCRIPTION'] = '';
	}
	if (isset($_POST['published']) && ($_POST['published'] == 'on')) {
		$good_info['PUBLISHED'] = 1;
	} else {
		$good_info['PUBLISHED'] = 0;
	}
	if (isset($_POST['sold']) && ($_POST['sold'] != '')) {
		if ($_POST['sold'] == 'on') {
			$good_info['SOLD'] = 1;
		} else {
			$good_info['SOLD'] = $_POST['sold'];
		}
	} else {
		$good_info['SOLD'] = 0;
	}

	$new_tags = array();
	if (isset($_POST['tags']) && (trim($_POST['tags']) != '') ) {
		$tags = explode(",", trim($_POST['tags'], ","));
		foreach($tags as $tag) {
			$new_tags[] = trim($tag);
		}
	}

	// запоминаем введённые данные
	$smarty->assign_by_ref("new_tags_list", $new_tags);
	$smarty->assign_by_ref("good_info", $good_info);

	// валидация данных
	if ($good_info['TITLE'] == '') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['EMPTY_TITLE']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good.tpl"));
		break;
	}

	// действие
	$status = catalog_db_modify_good($good_id, $good_info);
	if ($status == 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good.tpl"));
		break;
	}

	// внесем изменения по автору
	/*if ($authors_component) {
		$status = authors_db_update_author($good_id, $good_info['AUTHOR_ID']);
		if ($status == 'error') {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good.tpl"));
			break;
		}
	}

	// обновим инфу по авторам
	if ($authors_component) {

		$authors_list = authors_db_get_authors_list ();
		//show_ar($authors_list);

		$smarty->assign_by_ref("authors_list", $authors_list);

		$author_info = authors_db_get_authors_for_catalog_good($good_id);
		//show_ar($author_info);
		$smarty->assign_by_ref("author_info", $author_info);
	}*/

	// обновим тэги
	$status = catalog_db_set_good_tags($good_id, $new_tags);

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("msg", $_CATALOG_TEXTS['INFO_SAVED']);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good.tpl"));

} while (false);

?>