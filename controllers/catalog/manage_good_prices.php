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

	// базовая информация о лоте
	$good_info = catalog_db_get_good_info($good_id);
	if ($good_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	//$smarty->assign_by_ref("catalog_foto_params", $catalog_foto_params);
	$smarty->assign_by_ref("good_info", $good_info);
	//show_ar($good_info);

	// отправлена ли форма
	if (!isset($_POST['send'])) {

		// информация о ценах
		$goods_prices_info = catalog_db_get_all_prices($good_id);
		if ($goods_prices_info === 'error') {
			balo3_error("db error", true);
			exit;
		}
		if (isset($goods_prices_info[$good_id])) {
			$good_prices_list = $goods_prices_info[$good_id];
		} else {
			$good_prices_list = array();
		}
		$smarty->assign_by_ref("good_prices_list", $good_prices_list);
		//show_ar($good_prices_info);

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_prices.tpl"));
		break;
	}

	// добавление цены
	$new_price_info = array();
	if (isset($_POST['price_new']) && ($_POST['price_new'] != '')) {
		$new_price_info['PRICE'] = $_POST['price_new'];
	}
	if ( isset($_POST['currency_id_new']) && isset($currencies[$_POST['currency_id_new']]) ) {
		$new_price_info['CURRENCY_ID'] = $_POST['currency_id_new'];
	}
	if ( isset($_POST['type_new']) && in_array($_POST['type_new'], array('simple', 'hidden')) ) {
		$new_price_info['TYPE'] = $_POST['type_new'];
	} else {
		$new_price_info['TYPE'] = 'simple';
	}
	if ( count($new_price_info) > 0 && isset($new_price_info['PRICE']) ) {
		catalog_db_add_price($good_id, $new_price_info);
	}

	// изменения в существующих ценах и удаление цен
	foreach($_POST as $k=>$v) {
		if (($k == $v) && is_int($k)) {
			if (isset($_POST['del_'.$k]) && ($_POST['del_'.$k] == 'on')) {
				catalog_db_remove_price($k);
				continue;
			}
			$price_id = $k;
			$price_info['PRICE'] = $_POST['price_'.$k];
			$price_info['CURRENCY_ID'] = $_POST['currency_id_'.$k];
			catalog_db_modify_price($price_id, $price_info);
		}
	}

	// переполучим информацию о ценах
	$goods_prices_info = catalog_db_get_all_prices($good_id);
	if ($goods_prices_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	if (isset($goods_prices_info[$good_id])) {
		$good_prices_list = $goods_prices_info[$good_id];
	} else {
		$good_prices_list = array();
	}
	$smarty->assign_by_ref("good_prices_list", $good_prices_list);
	//show_ar($good_prices_info);

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("msg", $_CATALOG_TEXTS['INFO_SAVED']);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_prices.tpl"));

} while (false);

?>