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

	// информация о фотках
	$old_goods_fotos_info = catalog_db_get_fotos($good_id);
	if ($old_goods_fotos_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	if (isset($old_goods_fotos_info[$good_id])) {
		$old_good_fotos_list = $old_goods_fotos_info[$good_id];
	} else {
		$old_good_fotos_list = array();
	}

	// отправлена ли форма
	if (!isset($_POST['send'])) {

		$smarty->assign_by_ref("good_fotos_list", $old_good_fotos_list);

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_fotos.tpl"));
		break;
	}

	// добавление фото
	//show_ar($_FILES);exit;
	if ( isset($_FILES['new_foto']) && ($_FILES['new_foto']['error'] != 4) ) {

		$file_info = $_FILES['new_foto'];

		// добавляем в базу данных
		$good_foto_id = catalog_db_add_foto($good_id, array());
		if ($good_foto_id === "error") {
			echo "db error ";
			echo $_FARCH_FOTO_ERRORS['DB_ERROR'];
			exit;
		}

		// работаем с файлами, генерим превьюхи
		list($status, $error, $tech_info) = farch_fotos_save_foto("good_" . $good_id, $good_foto_id, $file_info);
		if ($status === "error") {
			catalog_db_remove_foto($good_foto_id);
			echo "$error ";
			echo $_FARCH_FOTO_ERRORS[$error];
			exit;
		}

		// всё в порядке, сохраняем прочую информацию
		// far_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
		// show_ar($tech_info);
		$status = catalog_db_modify_foto($good_foto_id, array("TECH_INFO"=>$tech_info));
		if ($status === "error") {
			echo "db error2 ";
			echo $_FARCH_FOTO_ERRORS['DB_ERROR2'];
			exit;
		}
	}

	// изменения в существующих фотках и удаление цен
	foreach($_POST as $k=>$v) {
		if (($k == $v) && is_int($k)) {
			if (isset($_POST['del_'.$k]) && ($_POST['del_'.$k] == 'on')) {
				catalog_db_remove_foto($k);
				farch_fotos_delete_foto_files("good_" . $good_id, array($k=>$old_good_fotos_list[$k]));
				continue;
			}
			if (preg_match("/^\d+$/", $_POST['sort_'.$k])) {
				$foto_info['SORT_VALUE'] = $_POST['sort_'.$k];
				catalog_db_modify_foto($k, $foto_info);
			}
		}
	}

	// переполучим информацию о фотках
	$goods_fotos_info = catalog_db_get_fotos($good_id);
	if ($goods_fotos_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	if (isset($goods_fotos_info[$good_id])) {
		$good_fotos_list = $goods_fotos_info[$good_id];
	} else {
		$good_fotos_list = array();
	}
	$smarty->assign_by_ref("good_fotos_list", $good_fotos_list);
	//show_ar($good_fotos_list);


	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("msg", $_CATALOG_TEXTS['INFO_SAVED']);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_fotos.tpl"));

} while (false);

?>