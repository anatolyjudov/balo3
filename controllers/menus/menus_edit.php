<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$menu_block_id = $_GET["id"];
	$info_menu_block = info_menu_block($menu_block_id);

	$path = $info_menu_block["PATH"];
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==false) {		//проверяем права
		echo "У вас нет прав на редактирование этого блока.";
		exit;
	}
	*/

	$superuser = true;
	$smarty->assign('superuser', $superuser);

	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', '/')==true) {		// отправляем в шаблон, что у юзера все права
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}
	*/

	$smarty->assign_by_ref('info_menu_block', $info_menu_block);

	if ($menus_farch_component) {

		if (isset($info_menu_block['IMAGE_FOTO_ID']) && ($info_menu_block['IMAGE_FOTO_ID'] != '')) {

			$info_menu_block['image'] = far_db_get_foto_info($info_menu_block['IMAGE_FOTO_ID']);
			if ($info_menu_block['image'] == 'error') {
				out_main($_ERRORS['DB_ERROR']);
			}
			if ($info_menu_block['image'] == 'notfound') {
				$info_menu_block['image'] = array();
			}
		}

	}


	balo3_controller_output($smarty->fetch("$templates_path/menus/menus_edit.tpl"));

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

	$menu_block_id = $_GET["id"];
	$info_menu_block = info_menu_block($menu_block_id);

	$path = $info_menu_block["PATH"];
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==false) {		//проверяем права
		echo "У вас нет прав на редактирование этого блока.";
		exit;
	}

	if (db_check_rights(get_user_id(), 'MODIFY_MENU', '/')==true) {		// отправляем в шаблон, что у юзера все права
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}

	$smarty->assign_by_ref('info_menu_block', $info_menu_block);

	if ($farch_component) {

		if (isset($info_menu_block['IMAGE_FOTO_ID']) && ($info_menu_block['IMAGE_FOTO_ID'] != '')) {

			$info_menu_block['image'] = far_db_get_foto_info($info_menu_block['IMAGE_FOTO_ID']);
			if ($info_menu_block['image'] == 'error') {
				out_main($_ERRORS['DB_ERROR']);
			}
			if ($info_menu_block['image'] == 'notfound') {
				$info_menu_block['image'] = array();
			}
		}

	}

	//show_ar($info_menu_block);

	// выдаем шаблон
	out_main($smarty->fetch("menus/menus_edit.tpl")); 
*/

?>