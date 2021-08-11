<?php

require_once("$entrypoint_path/components/farch/farch_init.php");

do {

	if (isset($_GET['album_id']) && preg_match("/^\d+$/", $_GET['album_id'])) {
		$smarty->assign("force_album_id", $_GET['album_id']);
	}

	// òýãè
	$tags_list = far_db_get_fototags();
	if ($tags_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("fototags_list", $tags_list);

	// èíôîðìàöèÿ îá àëüáîìàõ
	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);


	// âûäàåì øàáëîí
	balo3_controller_output($smarty->fetch("$templates_path/farch/fotos_manage.tpl"));

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

global $_ERRORS;

	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	$smarty->assign_by_ref("session_info", $session_info);
	//show_ar($session_info);

	// ÔÎÒÎÃÐÀÔÈÈ ÌÛ ÍÅ ÄÎÑÒÀÅÌ, ÏÎÒÎÌÓ ×ÒÎ ÈÕ ÌÎÆÅÒ ÁÛÒÜ ÌÍÎÃÎ
	// ÈÍÒÅÐÔÅÉÑ ÑÀÌ ÓÌÅÅÒ ÄÎÑÒÀÂÀÒÜ ÔÎÒÊÈ ×ÅÐÅÇ json api
	/*
	// ôîòêè
	$fotos_list = far_db_get_fotos_list();
	if ($fotos_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("fotos_list", $fotos_list);

	// ñîîòâåòñòâèå ôîòî è òýãîâ
	$rfts = far_db_get_fotos_fototags();
	$rfts = far_db_get_fototags();
	if ($rfts === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("rfts", $rfts);
	*/
/*
	if (isset($_GET['album_id']) && preg_match("/^\d+$/", $_GET['album_id'])) {
		$smarty->assign("force_album_id", $_GET['album_id']);
	}

	// òýãè
	$tags_list = far_db_get_fototags();
	if ($tags_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("fototags_list", $tags_list);

	// èíôîðìàöèÿ îá àëüáîìàõ
	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	out_main($smarty->fetch("farch/fotos_manage.tpl"));

*/
?>