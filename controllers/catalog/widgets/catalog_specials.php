<?php

function widget_catalog_specials($widget_params, $smarty) {
	global $components_path, $templates_path, $entrypoint_path;
	global $sections_info, $section_counts;

	// how many goods to show
	if (isset($args['limit'])) {
		$limit = $args['limit'];
	} else {
		$limit = 3;
	}

	//show_ar($sections_info);
	//show_ar($section_counts);

	$search_goods = catalog_db_get_good_ids_by_tags(array("_special"));
	if ( ($search_goods === 'error') || (count($search_goods) == 0) ) {
		return '';
	}

	$search_goods = catalog_db_filter_specials($search_goods, $limit);
	if ( ($search_goods === 'error') || (count($search_goods) == 0) ) {
		return '';
	}

	$goods_list = catalog_db_get_goods_list(array("good_ids"=>$search_goods));
	if ($goods_list === 'error') {
		return '';
	}
	$smarty->assign_by_ref("widget_goods_list", $goods_list);
	//show_ar($goods_list);

	// получим фоточки
	if (count($goods_list) > 0) {
		$goods_fotos_list = catalog_db_get_top_fotos(array_keys($goods_list));
		if ($goods_fotos_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("widget_goods_fotos_list", $goods_fotos_list);
	}

	// получим цены
	if (count($goods_list) > 0) {
		$goods_prices_list = catalog_db_get_prices(array_keys($goods_list));
		if ($goods_prices_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("widget_goods_prices_list", $goods_prices_list);
	}

	//show_ar($goods_list);

	return $smarty->fetch($templates_path . '/catalog/widgets/catalog_specials.tpl');

}

?>