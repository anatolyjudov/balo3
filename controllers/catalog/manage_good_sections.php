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

	// базовая информация о товаре
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

		// информация о дополнительных секциях
		$good_sections_list = catalog_db_get_good_additional_sections($good_id);
		if ($good_sections_list === 'error') {
			balo3_error("db error", true);
			exit;
		}
		$smarty->assign_by_ref("good_sections_list", $good_sections_list);

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_sections.tpl"));
		break;
	}

	// изменения в существующих привязках и удаление
	foreach($_POST as $k=>$v) {
		if (($k == $v) && is_int($k)) {
			$tmp_section_id = $k;
			if (isset($_POST['del_'.$tmp_section_id]) && ($_POST['del_'.$tmp_section_id] == 'on')) {
				catalog_db_remove_good_additional_section($good_id, $tmp_section_id);
				continue;
			}
		}
	}

	// добавление новой секции
	if (isset($_POST['new_section_id']) && ctype_digit($_POST['new_section_id']) && ($_POST['new_section_id'] != 0)) {
		$status = catalog_db_set_good_additional_section($good_id, $_POST['new_section_id']);
		if ($status === "error") {
			balo3_error("db error", true);
			exit;
		}
	}

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("msg", $_CATALOG_TEXTS['INFO_SAVED']);

	// получим информацию о cекциях
	$good_sections_list = catalog_db_get_good_additional_sections($good_id);
	if ($good_sections_list === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("good_sections_list", $good_sections_list);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_sections.tpl"));

} while (false);

?>