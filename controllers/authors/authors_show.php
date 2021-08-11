<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	$author_id = false;
	if (isset($balo3_request_info['path'][$authors_id_uri_level]) && ctype_digit($balo3_request_info['path'][$authors_id_uri_level])) {
		$author_id = $balo3_request_info['path'][$authors_id_uri_level];
	}

	// если нет id в $_GET, то выдаем список всех авторов
	if ($author_id === false) {

		$authors_list = authors_db_get_authors_list();
		if ($authors_list != "error") {
			$smarty->assign_by_ref("authors_list", $authors_list);
			//show_ar($authors_list);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
		}

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_list.tpl"));

	
	// если id есть, то выводим инфу по автору
	} else {

		// запрос инфо по автору
		$author_info = authors_db_get_author_by_id($author_id);
		if ($author_info == "error") {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/author.tpl"));
			break;
		}

		// существует ли автор с таким id
		if ($author_info['id'] != "") {
			$smarty->assign_by_ref("author_info", $author_info);
			//show_ar($author_info);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/author.tpl"));
			break;
		}

		// запрос связанных с автором лотов
		if ($catalog_component) {
			$goods_ids = authors_db_get_goods_id_by_author_id($author_info['id']);

			if ($_GET['s']=='ss') show_ar($goods_ids);

			if ($goods_ids == 0) {
				//$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
				balo3_controller_output($smarty->fetch("$templates_path/authors/author.tpl"));
				break;
			}

			$goods_info = array();
			foreach($goods_ids as $id) {
				$good_info = catalog_db_get_good_info($id['GOOD_ID']);
				if ($good_info === "error" || $good_info === "notfound") continue;
				$goods_info[$id['GOOD_ID']] = $good_info;
			}
			$smarty->assign_by_ref("goods_info", $goods_info);

			if ($_GET['s']=='ss') show_ar($goods_info);

			// получим фоточки
			if (isset($goods_info)) {
				$goods_fotos_list = catalog_db_get_top_fotos(array_keys($goods_info));
				
				if ($goods_fotos_list === 'error') {
					balo3_error("db error", true);
					exit;
				}

				$smarty->assign_by_ref("goods_fotos_list", $goods_fotos_list);
				//show_ar($goods_fotos_list);
			}
		}


		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/author.tpl"));
	}

} while (false);

?>