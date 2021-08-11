<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	//show_ar($balo3_request_info);
	$nodes = array_slice($balo3_request_info['path'], 1);
	//show_ar($nodes);
	if ($nodes === false) {
		balo3_error404();
		exit;
	}
	if (count($nodes) == 0) {
		antique_redirect_301($catalog_sections_uri);
		exit;
	}

	$last_dirname = $nodes[count($nodes)-1];
	if (ctype_digit($last_dirname)) {
		// id товара
		$old_id = $last_dirname;
		$good_info = catalog_db_get_good_info_old_id($old_id);
		//show_ar($good_info);
		if ( ($good_info === 'error') || ($good_info === 'notfound') ) {
			balo3_error404();
			exit;
		}
		$new_path = $catalog_collection_uri . $good_info['GOOD_ID'] . "/";
		antique_redirect_301($new_path);
		exit;
	} else {
		// секция
		if (($i = array_search($last_dirname, $sections_info['dirnames'])) === false) {
			balo3_error404();
			exit;
		}
		$new_path = $catalog_sections_uri;
		if (isset($sections_info['parents'][$i])) {
			foreach($sections_info['parents'][$i] as $parent) {
				$new_path .= $sections_info['list'][$parent]['DIRNAME'] . "/";
			}
		}
		$new_path .= $sections_info['list'][$i]['DIRNAME'] . "/";
		//echo $new_path;
		antique_redirect_301($new_path);
		exit;
		//show_ar($sections_info);
	}
	//echo $last_dirname;
	balo3_error404();
	exit;
	//balo3_controller_output(" ");

} while (false);




function antique_redirect_301($url) {
	header("HTTP/1.1 301 Moved Permanently");
	header("Location: " . $common_domain . $url);
	exit;
}

function catalog_db_get_good_info_old_id($old_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$q = "select * from $catalog_goods_table where _G_ID = $old_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row;
	}

	return "notfound";
}

?>