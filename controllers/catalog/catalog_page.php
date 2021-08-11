<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$dirnames = array_slice($balo3_request_info['path'], count(explode("/", trim($catalog_sections_uri, "/"))));
	//show_ar($dirnames);

	if (count($dirnames) > 0) {
		$sections_sequence = catalog_get_sections_ids($dirnames, $sections_info);
		//show_ar($sections_sequence);

		if (!catalog_validate_sections_sequence($sections_sequence, $sections_info)) {
			balo3_error404();
			exit;
		}

		$catalog_section_context = $sections_sequence[count($sections_sequence)-1];
		$catalog_section_context_extended = $sections_sequence;

	} else {
		$sections_sequence = array();
		$catalog_section_context = false;
	}


	// секция, по которой мы показываем лоты
	if (count($sections_sequence) > 0) {
		$section_id = $sections_sequence[count($sections_sequence)-1];
		$smarty->assign_by_ref("catalog_section_id", $section_id);
		$search_sections[] = $section_id;
		if (isset($sections_info['childs'][$section_id]['all_childs'])) {
			$search_sections = array_merge($search_sections, $sections_info['childs'][$section_id]['all_childs']);
		}
	} else {
		$smarty->assign("catalog_section_id", 0);
		$search_sections = array();
	}

	// получаем список лотов в разделе
	if (count($search_sections) == 1) {
		$goods_list = catalog_db_get_goods_list(array("section_id"=>$search_sections[0], "published"=>1), array("published"=>"desc", "sold"=>"asc", "complex_sort_value"=>"asc", "title"=>"asc"));
	} else {
		$goods_list = catalog_db_get_goods_list(array("section_ids"=>$search_sections, "published"=>1), array("published"=>"desc", "sold"=>"asc", "sort_value"=>"asc", "title"=>"asc"));
	}
	if ($goods_list === 'error') {
		balo3_error("db error", true);
		exit;
	}

	//asort($SOLD,SORT_DESC,$goods_list);

	//show_ar($goods_list); 
	//12:43
	//13:40


	$smarty->assign_by_ref("goods_list", $goods_list);

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


	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/catalog_page.tpl"));

} while (false);

?>

