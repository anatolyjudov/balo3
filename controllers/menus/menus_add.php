<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$title = $_POST["title"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];

	if ($menus_farch_component) {
		if (preg_match("/^\d+$/", $_POST['menu_image_foto_id'])) {
			$menu_image_foto_id = $_POST['menu_image_foto_id'];
		} else {
			$menu_image_foto_id = "";
		}
		$modify_menus_block = add_menu_block ($title, $comment, $path, $menu_image_foto_id);
	} else {
		$add_menu_block = add_menu_block ($title, $comment, $path);
	}

	// выдаем шаблон
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

	if (db_check_rights(get_user_id(), 'MODIFY_MENU', "/")==false) {		//проверяем права
		echo "да у вас и прав-то нет.";
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
		$modify_menus_block = add_menu_block ($title, $comment, $path, $menu_image_foto_id);
	} else {
		$add_menu_block = add_menu_block ($title, $comment, $path);
	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>