<?php

function farch_json_out($error_state, $result = array()) {
	echo json_encode(array($error_state, "info"=>$result));
	exit;
}

function farch_json_fotos_list() {
	global $_ERRORS;

	if (isset($_GET['skip']) && preg_match("/^\d+$/", $_GET['skip'])) {
		$skip = $_GET['skip'];
	} else {
		$skip = 0;
	}
	if (isset($_GET['limit']) && preg_match("/^\d+$/", $_GET['limit'])) {
		$limit = $_GET['limit'];
	} else {
		$limit = 0;
	}
	if (isset($_GET['tag']) && preg_match("/^\d+$/", $_GET['tag'])) {
		$tags_filter = array($_GET['tag']);
	} else {
		$tags_filter = array();
	}
	if (isset($_GET['album']) && preg_match("/^\d+$/", $_GET['album'])) {
		$albums_filter = array($_GET['album']);
	} else {
		$albums_filter = array();
	}

	$fotos_list = far_db_get_fotos_list($skip, $limit, $tags_filter, $albums_filter);
	if ($fotos_list === "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}

	//show_ar($fotos_list);
	$return_fotos_list = array();
	foreach($fotos_list as $foto_id=>$foto_info) {
		$fotos_list[$foto_id]['FOTO_TITLE'] = $fotos_list[$foto_id]['FOTO_TITLE'];
	}

	return array("ok", array_values($fotos_list));
}

function farch_json_foto_info() {
	global $_ERRORS, $_FARCH_ERRORS;

	if (!preg_match("/^\d+$/", $_POST['foto_id'])) {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	$foto_info = far_db_get_foto_info($_POST['foto_id']);
	if ($foto_info === "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}
	if ($foto_info === "notfound") {
		return array("error", array($_FARCH_ERRORS['NO_SUCH_FOTO_ID']));
	}

	$foto_info['FOTO_TITLE'] = $foto_info['FOTO_TITLE'];


	return array("ok", $foto_info);
}

function farch_json_foto_info_update() {
	global $_ERRORS, $_FARCH_ERRORS;

	if (!preg_match("/^\d+$/", $_POST['foto_id'])) {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	$foto_title = $_POST['foto_title'];

	$status = far_db_update_foto_title($_POST['foto_id'], $foto_title);
	if ($status === "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}

	return array("ok", $foto_info);

}

function farch_json_fotos_remove() {
	global $_ERRORS;

	if (isset($_POST['fotos_ids'])) {
		$fotos_ids = $_POST['fotos_ids'];
	} else {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	foreach(explode(",", $fotos_ids) as $foto_id) {
		$status = far_db_remove_foto($foto_id);
		if ($status == "error") {
			return array("error", array($_ERRORS['DB_ERROR']));
		}
		if ($status == "notfound") {
			return array("error", array($_ERRORS['BAD_PARAMETER']));
		}
	}

	return array("ok", array());
}

function farch_json_fotos_get_tags() {
	global $_ERRORS;

	if (isset($_POST['fotos_ids']) && preg_match("/^[\d\s,]+$/", $_POST['fotos_ids'])) {
		$fotos_ids = $_POST['fotos_ids'];
	} else {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	$r_fotos_tags_info = far_db_get_fotos_fototags_status($fotos_ids);
	if ($r_fotos_tags_info == "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}

	return array("ok", $r_fotos_tags_info);
}

function farch_json_fotos_save_tags() {
	global $_ERRORS;

	if (isset($_POST['fotos_ids']) && preg_match("/^[\d\s,]+$/", $_POST['fotos_ids'])) {
		$fotos_ids = explode(",", $_POST['fotos_ids']);
	} else {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	$clear_tags_ids = array();
	$checked_tags_ids = array();
	foreach($_POST as $k=>$v) {
		if (preg_match("/^fototag_id_(\d)+$/", $k, $matches)) {
			$fototag_id = $matches[1];
			if ($v == 'clear') {
				$clear_tags_ids[] = $fototag_id;
				continue;
			}
			if ($v == 'checked') {
				$checked_tags_ids[] = $fototag_id;
				continue;
			}
		}
	}
	$status = far_db_clear_fototags_from_fotos($clear_tags_ids, $fotos_ids);
	if ($status == "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}
	$status = far_db_set_fototags_to_fotos($checked_tags_ids, $fotos_ids);
	if ($status == "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}

	return array("ok", $r_fotos_tags_info);
}

function farch_json_fotos_save_order() {
	global $_ERRORS;

	// fs[]=18&fs[]=19&fs[]=20&fs[]=22&fs[]=23&fs[]=24&fs[]=25

	if (!isset($_GET['fs'])) {
		return array("error", array($_ERRORS['BAD_PARAMETER']));
	}

	$fotos_order = $_GET['fs'];
	$fotos_sort_values = array_flip($fotos_order);
	$status = far_db_save_fotos_sort_values($fotos_sort_values);
	if ($status == "error") {
		return array("error", array($_ERRORS['DB_ERROR']));
	}

	//show_ar($fotos_sort_values);
	return array("ok", array());
}

function farch_json_foto_upload() {
	global $_ERRORS;
	global $_FARCH_FOTO_ERRORS;
	global $files_path;

/*
	$filename = basename($_FILES['uf_upload_file']['name']);
	if (move_uploaded_file($_FILES['uf_upload_file']['tmp_name'], $files_path.'/' . $filename)) {
		$data = array('filename' => $filename);
	} else {
		$data = array('error' => 'Failed to save');
	}
*/
/*
	if (isset($_POST['album_id']) && preg_match("/^\d+$/", $_POST['album_id'])) {
		$album_id = $_POST['album_id'];
	} else {
		return array('error', array('album not found'));
	}
*/

	if (isset($_POST['album_foto'])) {
		if (!is_array($_POST['album_foto'])) {
			$album_foto = array($_POST['album_foto']);
		} else {
			$album_foto = $_POST['album_foto'];
		}
	} else {
		$album_foto = array();
	}

	//show_ar($album_foto);
	
	$album_id = $album_foto[0];
	if (!preg_match("/^\d+$/", $album_id)) {
		echo 'wrong album id';
		exit;
		//return array('error', array('wrong album id'));
	}

	$file_info = $_FILES['Filedata'];
	// show_ar($file_info);
	// статус 4 - не загруженный файл
	if ($file_info['error'] == 4) {
		echo 'bad file upload';
		exit;
		//return array('error', array('bad file upload'));
	}

	// номер файла
	// (в форме закачки файлов поля называются fN, где N - число)
	$file_number = 0;

	// добавляем в базу данных
	$foto_id = far_db_create_foto_info("", "", "", $album_id);
	if ($foto_id === "error") {
		echo $_FARCH_FOTO_ERRORS['DB_ERROR'];
		exit;
	}

	// работаем с файлами, генерим превьюхи
	list($status, $error, $tech_info) = farch_fotos_save_foto($album_id, $foto_id, $file_info);
	if ($status === "error") {
		far_db_remove_foto($foto_id);
		echo $_FARCH_FOTO_ERRORS[$error];
		exit;
	}

	// всё в порядке, сохраняем прочую информацию
	// far_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
	$status = far_db_update_foto_info($foto_id, "", $tech_info);
	if ($status === "error") {
		echo $_FARCH_FOTO_ERRORS['DB_ERROR2'];
		exit;
	}

	// сохраняем и тэг, если требуется
	if (isset($_POST['tag_foto'])) {
		if (!is_array($_POST['tag_foto'])) {
			$tag_foto = array($_POST['tag_foto']);
		} else {
			$tag_foto = $_POST['tag_foto'];
		}
		if (count($_POST['tag_foto']) > 0) {
			foreach($tag_foto as $t) {
				if (!preg_match("/^\d+$/", $t)) {
					echo 'error in tags';
					exit;
				}
			}
			$status = far_db_set_fototags_to_fotos($tag_foto, array($foto_id));
			if ($status == "error") {
				echo $_FARCH_FOTO_ERRORS['DB_ERROR'];
				exit;
			}
		}
	}

	echo "1";
	exit;
	//return array("ok", $data);

}


?>