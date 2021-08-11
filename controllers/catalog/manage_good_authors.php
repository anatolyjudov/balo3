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

	$old_good_info = catalog_db_get_good_info($good_id);
	if ($old_good_info === 'error') {
		balo3_error("db error", true);
		exit;
	}

	if (!isset($_POST['confirmed'])) {

		// данные об авторе лота
		if ($authors_component) {

			$author_info = authors_db_get_authors_for_catalog_good($good_id);
			//show_ar($author_info);
			
			$authors_list = authors_db_get_authors_list ();
			//show_ar($authors_list);

			$smarty->assign_by_ref("author_info", $author_info);
			$smarty->assign_by_ref("authors_list", $authors_list);
		}

		$smarty->assign_by_ref("good_info", $old_good_info);

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_authors.tpl"));
		break;
	}

	// прием параметров
	$good_info = array(
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
	}

	// запоминаем введённые данные
	$smarty->assign_by_ref("good_info", $good_info);

	// внесем изменения по автору
	if ($authors_component) {
		$status = authors_db_update_author($good_id, $good_info['AUTHOR_ID']);
		if ($status == 'error') {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_authors.tpl"));
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
	}

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("msg", $_CATALOG_TEXTS['INFO_SAVED']);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_authors.tpl"));

} while (false);

?>