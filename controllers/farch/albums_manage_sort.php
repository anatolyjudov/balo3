<?php

require_once("$entrypoint_path/components/farch/farch_init.php");

do {

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// принмаем параметры сортировки
	foreach(array_keys($_POST) as $k) {
		if (preg_match("/^sort_(\d+)$/", $k, $matches)) {
			$album_id = $matches[1];
			$sort_value = $_POST[$k];
			if (preg_match("/^\d+$/", $sort_value)) {
				$status = far_db_modify_album($album_id, array("SORT_VALUE"=>$sort_value));
				if ($status == 'error') {
					balo3_controller_output($_ERRORS['DB_ERROR']);
					break;
				}
			}
		}
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_sort.tpl"));

} while (false);

?>



<?php
/*
include_once($file_path."/includes/farch/farch_db.php");
include_once($file_path."/includes/farch/farch_config.php");
include_once($file_path."/includes/farch/farch_functions.php");
include_once($file_path."/includes/farch/farch_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS, $_FARCH_ERRORS;

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// принмаем параметры сортировки
	foreach(array_keys($_POST) as $k) {
		if (preg_match("/^sort_(\d+)$/", $k, $matches)) {
			$album_id = $matches[1];
			$sort_value = $_POST[$k];
			if (preg_match("/^\d+$/", $sort_value)) {
				$status = far_db_modify_album($album_id, array("SORT_VALUE"=>$sort_value));
				if ($status == 'error') {
					out_main($_ERRORS['DB_ERROR']);
				}
			}
		}
	}

	// вывод шаблона
	out_main($smarty->fetch("farch/albums_manage_sort.tpl"));
*/
?>