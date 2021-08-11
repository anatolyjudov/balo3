<?php

require_once("$components_path/phpmorphy/phpmorphy_init.php");
require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$catalog_search_query = "";
	$smarty->assign_by_ref("catalog_search_query", $catalog_search_query);
	if (isset($_GET['s'])) {
		$catalog_search_query = $_GET['s'];
	}

	// составляем набор слов
	$search_words = phpmorphy_get_search_words($catalog_search_query);

	// определим только видимые разделы и будем искать по ним
	$search_in_sections = array();
	foreach(array_keys($sections_info['list']) as $tmp_section_id) {
		if ($sections_info['list'][$tmp_section_id]['PUBLISHED'] == 1) {
			$search_in_sections[] = $tmp_section_id;
		}
	}

	// получаем список лотов по этому запросу
	//$goods_list = catalog_db_get_goods_list(array("search_query"=>$catalog_search_query, "published"=>1), array("published"=>"desc", "sort_value"=>"asc", "title"=>"asc"));
	//$goods_list = catalog_db_search_goods($catalog_search_query, array("published"=>1), array("published"=>"desc", "sort_value"=>"asc", "title"=>"asc"));
	$goods_list = catalog_db_new_search_goods($search_words, array("published"=>1, "strict_section_ids"=>$search_in_sections), array("published"=>"desc", "sort_value"=>"asc", "title"=>"asc"));
	if ($goods_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("goods_list", $goods_list);

	$goods_count = count($goods_list);
	$smarty->assign_by_ref("goods_count", $goods_count);

	// получим фоточки
	if (count($goods_list) > 0) {
		$goods_fotos_list = catalog_db_get_top_fotos(array_keys($goods_list));
		if ($goods_fotos_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("goods_fotos_list", $goods_fotos_list);
		//show_ar($goods_fotos_list);
	}

	// получим цены
	if (count($goods_list) > 0) {
		$goods_prices_list = catalog_db_get_prices(array_keys($goods_list));
		if ($goods_prices_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("goods_prices_list", $goods_prices_list);
		//show_ar($goods_fotos_list);
	}
	//show_ar($goods_list);

	/* структурируем результат по секциям */
	$found_sections = array();
	foreach($goods_list as $good_id => $good_info) {
		$found_sections[$good_info['SECTION_ID']]['good_ids'][$good_id] = 1;
	}


	// дополнительно определим список разделов, которые тоже подходят под результаты поиска
	//show_ar($sections_info);

	foreach(array_keys($found_sections) as $found_section_id) {
		$sort = array_search($found_section_id, $sections_info['plain']);
		$found_sections[$found_section_id]['_sort'] = ($sort === false) ? 0 : $sort;
		$found_sections[$found_section_id]['parents'] = $sections_info['parents'][$found_section_id];
	}

	uasort($found_sections, function($a, $b) {
		if ($a['_sort'] > $b['_sort']) {
			return 1;
		} elseif ($a['_sort'] < $b['_sort']) {
			return -1;
		} else {
			return 0;
		}
	});

	//show_ar($found_sections);
	$smarty->assign_by_ref("catalog_found_sections", $found_sections);

	/*
	$found_sections = array();
	if (strlen($catalog_search_query) > 2 && strlen($catalog_search_query) < 128) {
		foreach($sections_info['list'] as $tmp_section_id => $tmp_section_info) {

			if ($tmp_section_info['PUBLISHED'] == 0) continue;

			$csq = mb_strtolower($catalog_search_query, $common_mbstr_encoding);

			$state = mb_stristr(mb_strtolower($tmp_section_info['SECTION_NAME'], $common_mbstr_encoding), $csq, false, $common_mbstr_encoding);
			if ($state !== false) {
				$found_sections[] = $tmp_section_id;
			}

		}
	}
	$smarty->assign_by_ref("catalog_found_sections", $found_sections);
	//show_ar($found_sections);
	*/

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/search_page.tpl"));

} while (false);

?>

