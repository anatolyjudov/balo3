<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	$l = count(explode("/", trim($catalog_admin_uri, "/")));
	$section_id = $balo3_request_info['path'][$l+1];
	$smarty->assign_by_ref("section_id", $section_id);
	//$smarty->assign_by_ref("catalog_foto_params", $catalog_foto_params);

	if (!isset($_POST['title'])) {
		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/new_good.tpl"));
		break;
	}

	// прием параметров
	$good_info = array();
	if (isset($_POST['title'])) {
		$good_info['TITLE'] = $_POST['title'];
	} else {
		$good_info['TITLE'] = '';
	}

	$smarty->assign_by_ref("good_info", $good_info);

	// валидация
	if ($good_info['TITLE'] == '') {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/new_good.tpl"));
		break;
	}

	// действие
	$good_id = catalog_db_add_good($section_id, $good_info['TITLE']);
	if ($good_id == 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/new_good.tpl"));
		break;
	}

	// вывод об успешном создании
	$smarty->assign_by_ref("good_id", $good_id);
	balo3_controller_output($smarty->fetch("$templates_path/catalog/add_good.tpl"));
	break;

} while (false);

?>