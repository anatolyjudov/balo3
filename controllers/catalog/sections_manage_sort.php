<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	// принмаем параметры сортировки
	foreach(array_keys($_POST) as $k) {
		if (preg_match("/^sort_(\d+)$/", $k, $matches)) {
			$section_id = $matches[1];
			$sort_value = $_POST[$k];
			if (preg_match("/^\d+$/", $sort_value)) {
				$status = catalog_db_modify_section($section_id, array("SORT_VALUE"=>$sort_value));
				if ($status == 'error') {
					balo3_controller_output($_ERRORS['DB_ERROR']);
					break;
				}
			}
		}
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_sort.tpl"));

} while (false);

?>