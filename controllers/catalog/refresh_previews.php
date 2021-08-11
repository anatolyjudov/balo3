<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	echo "<h1>Refresh fotos</h1><br>";

	$fotos_list = catalog_db_get_all_fotos();
	//show_ar($fotos_list);

	foreach($fotos_list as $good_foto_id => $foto_info) {

		$file_info = $foto_info['TECH_INFO']['original_image_info'];

		$good_id = $foto_info['GOOD_ID'];

		$filetype = $foto_info['TECH_INFO']['extension'];
		$new_filename = "$good_foto_id.$filetype";

		$file_info['name'] = $new_filename;
		$file_info['tmp_name'] = $farch_foto_params['folders']['file'] . "/" . "good_" . $good_id . "/" . $new_filename;

		//show_ar($file_info);

		
		// работаем с файлами, генерим превьюхи
		list($status, $error, $tech_info) = farch_fotos_save_foto("good_" . $good_id, $good_foto_id, $file_info);
		if ($status === "error") {
			//catalog_db_remove_foto($good_foto_id);
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
		

		//echo "stop after 1 round";
		//exit;

		echo $good_foto_id . " done \r\n";
		ob_flush();

	}

	balo3_controller_output(" ");

} while (false);



function cat_refresh_foto($good_id, $file_info) {


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


?>