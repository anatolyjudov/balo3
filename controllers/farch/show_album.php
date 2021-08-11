<?
include_once($file_path."/includes/farch/farch_db.php");
include_once($file_path."/includes/farch/farch_config.php");
include_once($file_path."/includes/farch/farch_functions.php");
include_once($file_path."/includes/farch/farch_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $farch_album_uri_level_user;

	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// определяем id альбома
	// show_ar($params);
	if (isset($params['path'][$farch_album_uri_level_user]) && preg_match("/^\d+$/", $params['path'][$farch_album_uri_level_user])) {
		$album_id = $params['path'][$farch_album_uri_level_user];
	} else {
		error404();
		exit;
	}
	$smarty->assign_by_ref("album_id", $album_id);

	//echo $album_id;

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

	$album_description_info = far_db_get_album_description_info($album_id);
	if ($album_description_info === "error") {
		out_main($_ERRORS['DB_ERROR']);
	}
	if ($album_description_info === "notfound") {
		out_main($_ERRORS['BAD_PARAMETER']);
	}
	$smarty->assign_by_ref("album_description_info", $album_description_info);

	//show_ar($albums_info);

	// достанем фотки в альбоме
	// far_db_get_fotos_list($skip = 0, $limit = 0, $fototag_filter = array(), $albums_filter = array())
	$skip = 0;
	$limit = 0;
	$fototag_filter = array();
	$albums_filter = array($album_id);
	$fotos_list = far_db_get_fotos_list($skip, $limit, $fototag_filter, $albums_filter);
	if ($fotos_list === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("fotos_list", $fotos_list);

	//show_ar($fotos_list);

	out_main($smarty->fetch("farch/show_album.tpl"));
?>