<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$id = $_POST["id"];
	$info_menu_block = info_menu_block($id);

	$path = $info_menu_block["PATH"];
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==false) {		//провер€ем права
		echo "” вас нет прав на редактирование этого блока.";
		exit;
	}*/


	$title = $_POST["title"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];

	if ($menus_farch_component) {
		if (preg_match("/^\d+$/", $_POST['menu_image_foto_id'])) {
			$menu_image_foto_id = $_POST['menu_image_foto_id'];
		} else {
			$menu_image_foto_id = "";
		}
		$modify_menus_block = modify_menu_block ($id, $title, $comment, $path, $menu_image_foto_id);
	} else {
		$modify_menus_block = modify_menu_block ($id, $title, $comment, $path);
	}

	$smarty->clear_cache(null, 'modules|menus_blocks');

	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback2.tpl"));

} while (false);

?>




<?php
/*

include($file_path."/includes/menus/menus_config.php");
include($file_path."/includes/menus/menus_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;


	$id = $_POST["id"];
	$info_menu_block = info_menu_block($id);

	$path = $info_menu_block["PATH"];
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==false) {		//провер€ем права
		echo "” вас нет прав на редактирование этого блока.";
		exit;
	}

	$title = $_POST["title"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];

	if ($farch_component) {
		if (preg_match("/^\d+$/", $_POST['menu_image_foto_id'])) {
			$menu_image_foto_id = $_POST['menu_image_foto_id'];
		} else {
			$menu_image_foto_id = "";
		}
		$modify_menus_block = modify_menu_block ($id, $title, $comment, $path, $menu_image_foto_id);
	} else {
		$modify_menus_block = modify_menu_block ($id, $title, $comment, $path);
	}

	$smarty->clear_cache(null, 'modules|menus_blocks');


	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>