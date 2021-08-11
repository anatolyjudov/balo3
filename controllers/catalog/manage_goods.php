<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	if (count($balo3_request_info['path']) == 3) {
		// all goods mode
		$controller_mode = "all";

	} else {
		// section mode
		$controller_mode = "section";
		$l = count(explode("/", trim($catalog_admin_uri, "/")));
		$section_id = $balo3_request_info['path'][$l+1];
		$smarty->assign_by_ref("section_id", $section_id);
	}

	// фильтры
	if ($controller_mode == 'section') {
		$filters = array("section_id"=>$section_id);
		$sort_order = array("published"=>"desc", "sold"=>"asc", "complex_sort_value"=>"asc", "title"=>"asc");
	} else {
		$filters = array();
		$sort_order = array("published"=>"desc", "sold"=>"asc", "title"=>"asc");
	}

	if (isset($_GET['search_str']) && ($_GET['search_str'] != '')) {
		$filters['search_str'] = $_GET['search_str'];
		$smarty->assign_by_ref("catalog_search_str", $filters['search_str']);
	}

	$goods_list = catalog_db_get_goods_list($filters, $sort_order);
	// show_ar($goods_list);
	if ($goods_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("goods_list", $goods_list);
	//$smarty->assign_by_ref("catalog_foto_params", $catalog_foto_params);

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
		$goods_prices_list = catalog_get_user_prices(array_keys($goods_list));
		if ($goods_prices_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("goods_prices_list", $goods_prices_list);
		//show_ar($goods_prices_list);
		/*
    [3783] => Array
        (
            [price] => 15000
            [currency] => 0
            [type] => 
        )

    [3495] => Array
        (
            [price] => 
            [currency] => 
            [type] => no_price
        )
		*/
	}


	if (isset($_GET['ok'])) {
		if ($_GET['ok'] == 'sort') {
			$smarty->assign("msg", $_CATALOG_TEXTS['GOODS_SORTED']);
		}
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_goods.tpl"));

} while (false);

?>