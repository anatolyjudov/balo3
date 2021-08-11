<?
include_once($file_path."/includes/isetane/isetane_db.php");
include_once($file_path."/includes/isetane/isetane_config.php");
include_once($file_path."/includes/isetane/isetane_functions.php");
include_once($file_path."/includes/isetane/isetane_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	$smarty->assign_by_ref("isetane_foto_params", $isetane_foto_params);


	// вывод формы
	if (!isset($_POST['upload'])) {
		out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
	}

	// добавление фотографий
	// перебор всех отправленных файлов
	foreach($_FILES as $k=>$file_info) {

		// статус 4 - не загруженный файл
		if ($file_info['error'] == 4) {
			continue;
		}

		// номер файла
		// (в форме закачки файлов поля называются fN, где N - число)
		if (!preg_match("/^f(\d+)$/", $k, $matches)) {
			// какой-то левый файл, ну его
			continue;
		}
		$file_number = $matches[1];

		// добавляем в базу данных
		$foto_id = istn_db_create_foto_info("", "", "");
		if ($foto_id === "error") {
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		// работаем с файлами, генерим превьюхи
		list($status, $error, $tech_info) = isetane_fotos_save_foto($foto_id, $file_info);
		if ($status === "error") {
			istn_db_remove_foto($foto_id);
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS[$error]);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		// всё в порядке, сохраняем прочую информацию
		// istn_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
		$status = istn_db_update_foto_info($foto_id, "", $tech_info);
		if ($status === "error") {
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS['DB_ERROR2']);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		//show_ar($tech_info);

	}

	$smarty->assign("msg_add", $_ISTN_FOTO_MESSAGES['SUCCESSFUL_UPLOAD']);
	out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));

?>