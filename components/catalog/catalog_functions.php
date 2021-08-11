<?php

function catalog_refresh_specials() {
	global $catalog_specials_include_sections;
	global $catalog_specials_lasttime_file;
	global $catalog_specials_count;
	global $sections_info;

	if (is_file($catalog_specials_lasttime_file)) {
		$lasttime = file_get_contents($catalog_specials_lasttime_file);
		if ((time() - $lasttime) < 86400) return;
	} else {
		$fp = fopen($catalog_specials_lasttime_file, "w");
		fputs($fp, time());
		fclose($fp);
		if (!is_file($catalog_specials_lasttime_file)) {
			return;
		}
	}

	$search_sections = array();
	foreach($catalog_specials_include_sections as $section_id) {
		$search_sections[] = $section_id;
		if (isset($sections_info['childs'][$section_id]['all_childs'])) {
			$search_sections = array_merge($search_sections, $sections_info['childs'][$section_id]['all_childs']);
		}
	}

	//show_ar($search_sections);



	$final_goods_list = array();
	$final_goods_fotos_list = array();
	$iterations = 0;
	while( count($final_goods_list) < $catalog_specials_count ) {

		$iterations++;
		//echo $iterations . "<br>\r\n";
		if ($iterations > 10) break;

		// получаем список лотов в разделе
		$tmp_goods_list = catalog_db_get_goods_list(array("section_ids"=>$search_sections, "published"=>1), array("rand"=>"1"), array("limit" => $catalog_specials_count));
		if ($tmp_goods_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		//show_ar($goods_list);

		// получим фоточки
		if (count($tmp_goods_list) > 0) {
			$tmp_goods_fotos_list = catalog_db_get_top_fotos(array_keys($tmp_goods_list));
			if ($tmp_goods_fotos_list === 'error') {
				balo3_error("db error", true);
				exit;
			}

			//show_ar($tmp_goods_fotos_list);
		}


		// берем только с вертикальными фоточками
		foreach($tmp_goods_fotos_list as $good_id=>$gfi) {
			if ( $gfi['TECH_INFO']['original_image_info'][0] >= $gfi['TECH_INFO']['original_image_info'][1] ) {
				// wide
			} else {
				// narrow
				$final_goods_list[] = $good_id;
				//$final_goods_fotos_list[$good_id] = $tmp_goods_fotos_list[$good_id];

				if (count($final_goods_list) == $catalog_specials_count) {
					break;
				}

			}
		}


	}

	//show_ar($final_goods_list);
	//show_ar($final_goods_fotos_list);

	$status = catalog_db_replace_specials_tag($final_goods_list);
	if ($status === 'error') {
		balo3_error("db error", true);
		exit;
	}

	$fp = fopen($catalog_specials_lasttime_file, "w");
	fputs($fp, time());
	fclose($fp);

	return;
}

function catalog_get_sections_ids($dirnames, $sections_info) {

	//show_ar($dirnames);

	$result = $dirnames;
	foreach($dirnames as $n => $dirname) {
		if ( ctype_digit($dirname) && isset($sections_info['list'][$dirname]) ) {
			$result[$n] = $dirname;
			continue;
		}
		$id = array_search($dirname, $sections_info['dirnames']);
		if ($id === false) {
			return false;
		}
		$result[$n] = $id;
	}
	return $result;

}

function catalog_validate_sections_sequence($sections_sequence, $sections_info) {

	if ( !isset($sections_info['list'][$sections_sequence[0]]) ) {
		return false;
	}

	for($i=0;$i<count($sections_sequence)-1;$i++) {
		$t0 = $sections_sequence[$i];
		$t1 = $sections_sequence[$i+1];
		//echo $t0 . " -> " . $t1 . " <br>";
		if ( isset($sections_info['childs'][$t0]) && in_array($t1, $sections_info['childs'][$t0]['childs']) ) {
		} else {
			return false;
		}
	}

	return true;

}

function catalog_get_user_prices($good_ids) {
	global $catalog_price_rules;
	global $currencies;

	$return_value = array();

	// получим цены
	$goods_prices_list = catalog_db_get_prices($good_ids);
	if ($goods_prices_list === 'error') {
		return "error";
	}

	if (count($goods_prices_list) == 0) {
		return $return_value;
	}

	foreach(array_keys($goods_prices_list) as $good_id) {
		$return_value[$good_id] = array(
			"price"=>false,
			"currency"=>false,
			"type"=>"no_price"
		);
		foreach($goods_prices_list[$good_id] as $price_info) {

			if ($price_info['CURRENCY_ID'] == $catalog_price_rules['preferred_currency']) {
				$return_value[$good_id] = array(
						"price"=>$price_info['PRICE'],
						"currency"=>$price_info['CURRENCY_ID'],
						"type"=>$price_info['TYPE']
					);
				break;
			}

			if ($catalog_price_rules['recount_price']) {
				if ($currencies[ $catalog_price_rules['preferred_currency']]['EQUALS'] == 0) {
					continue;
				}
				$recounted_price = ceil($price_info['PRICE'] * $currencies[$price_info['CURRENCY_ID']]['EQUALS'] / $currencies[ $catalog_price_rules['preferred_currency']]['EQUALS']);
				$return_value[$good_id] = array(
					"price" => $recounted_price,
					"currency" => $catalog_price_rules['preferred_currency'],
					"type" => $price_info['TYPE']
				);
			}

		}
	}

	return $return_value;

}

function catalog_get_user_price($good_id) {
	global $catalog_price_rules;
	global $currencies;

	$return_value = array(
		"price"=>false,
		"currency"=>false,
		"type"=>"no_price"
	);

	// получим цены
	$goods_prices_list = catalog_db_get_prices(array($good_id));
	if ($goods_prices_list === 'error') {
		return "error";
	}

	if (count($goods_prices_list) == 0) {
		return $return_value;
	}

	foreach($goods_prices_list[$good_id] as $price_info) {

		if ($price_info['CURRENCY_ID'] == $catalog_price_rules['preferred_currency']) {
			$return_value = array(
					"price"=>$price_info['PRICE'],
					"currency"=>$price_info['CURRENCY_ID'],
					"type"=>$price_info['TYPE']
				);
			return $return_value;
		}

		if ($catalog_price_rules['recount_price']) {
			if($currencies[ $catalog_price_rules['preferred_currency']]['EQUALS'] != 0) {
				$recounted_price = ceil($price_info['PRICE'] * $currencies[$price_info['CURRENCY_ID']]['EQUALS'] / $currencies[ $catalog_price_rules['preferred_currency']]['EQUALS']);
				$return_value = array(
					"price" => $recounted_price,
					"currency" => $catalog_price_rules['preferred_currency'],
					"type" => $price_info['TYPE']
				);
			}
		}

	}

	return $return_value;

}

?>