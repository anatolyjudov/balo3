<?
include_once($file_path."/includes/farch/farch_db.php");
include_once($file_path."/includes/farch/farch_config.php");
include_once($file_path."/includes/farch/farch_functions.php");
include_once($file_path."/includes/farch/farch_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// тэги
	$tags_list = far_db_get_fototags();
	if ($tags_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("fototags_list", $tags_list);

	// информация об альбомах
	$albums_info = far_db_get_albums(true);
	if ($albums_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("albums_info", $albums_info);

	//show_ar($albums_info);

	out_main($smarty->fetch("farch/show_albums.tpl"));
?>