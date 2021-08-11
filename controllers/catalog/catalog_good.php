<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$good_id = $balo3_request_info['path'][ count(explode("/", trim($catalog_collection_uri, "/"))) ];
	if (!ctype_digit($good_id)) {
		balo3_error404();
		exit;
	}

	// получим информацию о лоте
	$good_info = catalog_db_get_good_info($good_id);
	if ($good_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	if ($good_info === 'notfound') {
		balo3_error404();
		exit;
	}
	$smarty->assign_by_ref("good_id", $good_id);
	$smarty->assign_by_ref("good_info", $good_info);

	// дополнительные разделы, к которым привязан товар
	$good_sections_list = catalog_db_get_good_additional_sections($good_id);
	if ($good_sections_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$good_sections_list_ids = array_keys($good_sections_list);

	// будем определять контекст и проверять, не закрыты ли все разделы, к которым относится товар
	{
		// по умолчанию преполагаем, что контекст - это основной раздел товара
		$possible_section_context = $good_info['SECTION_ID'];

		// контекст может быть передан в параметре ref, если мы пришли из дополнительного раздела
		if (isset($_REQUEST['ref']) && ctype_digit($_REQUEST['ref']) && isset($good_sections_list[$_REQUEST['ref']])) {
			$possible_section_context = $_REQUEST['ref'];
			// в таком случае добавим основную секцию в массив секций, чтобы в случае если предполагаемый раздел закрыт, проверять и основной тоже
			array_splice($good_sections_list_ids, 0, 0, $good_info['SECTION_ID']);
		}

		// проверка, не закрыт ли весь раздел
		if ($sections_info['list'][$possible_section_context]['PUBLISHED'] == 0) {

			$catalog_section_context = false;
			// проверяем, не закрыты ли дополнительные разделы, если они есть у товара
			foreach($good_sections_list_ids	as $a_s_id) {
				if ( $sections_info['list'][$a_s_id]['PUBLISHED'] == 1 ) {
					$catalog_section_context = $a_s_id;
					break;
				}
			}
			// если не найдено ни одного опубликованного раздела, значит товар совсем скрыт
			if (false === $catalog_section_context) {
				common_error404();
				exit;
			}
		} else {
			$catalog_section_context = $possible_section_context;
		}

		// уточняем контекст
		if (isset($sections_info['parents'][$catalog_section_context])) {
			$catalog_section_context_extended = $sections_info['parents'][$catalog_section_context];
			$catalog_section_context_extended[] = $catalog_section_context;
			//show_ar($catalog_section_context_extended);
		} else {
			$catalog_section_context_extended = array($catalog_section_context);
		}
	}

	// получаем список лотов в разделе
	$goods_list = catalog_db_get_goods_list(array("section_ids"=>$search_sections, "published"=>1), array("published"=>"desc", "sort_value"=>"asc", "title"=>"asc"));
	if ($goods_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("goods_list", $goods_list);

	// получим фоточки лота
	$goods_fotos_list = catalog_db_get_fotos(array($good_id));
	if ($goods_fotos_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("goods_fotos_list", $goods_fotos_list);
	//show_ar($goods_fotos_list);

	// цена
	$good_price = catalog_get_user_price($good_id);
	if ($good_price === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("good_price", $good_price);
	//show_ar($good_price);

	//данные об авторе лота
	if ($authors_component) {
		$author_info = authors_db_get_authors_for_catalog_good($good_id);
		//show_ar($author_info);
		$smarty->assign_by_ref("author_info", $author_info);
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/catalog_good.tpl"));

} while (false);

?>

