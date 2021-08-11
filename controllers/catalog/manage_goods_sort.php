<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$l = count(explode("/", trim($catalog_admin_uri, "/")));
	$section_id = $balo3_request_info['path'][$l+1];
	//$good_id = $balo3_request_info['path'][$l+4];
	$smarty->assign_by_ref("section_id", $section_id);

	// принимаем параметры сортировки
	foreach($_POST as $k=>$v) {
		if ($k==$v && preg_match("/^\d+$/", $k)) {
			$good_id = $k;
			if (isset($_POST['sort_'.$k])) {
				$good_info['SORT_VALUE'] = $_POST['sort_'.$k];
				catalog_db_modify_good($good_id, $good_info);
			}
			if (isset($_POST['complex_sort_'.$k]) && ctype_digit($_POST['complex_sort_'.$k])) {
				catalog_db_update_good_additional_section_info($good_id, $section_id, $_POST['complex_sort_'.$k]);
			}
		}
	}


	if (headers_sent()) {
		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_goods_sort.tpl"));
		break;
	} else {
		header("Location: $root_path/admin/catalog/sections/$section_id/goods/?ok=sort", true);
		exit;
	}


} while (false);

?>