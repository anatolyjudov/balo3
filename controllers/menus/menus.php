<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$menu_blocks = list_menu_blocks();
	$i = 1;
	if ($menu_blocks === "error") {
		balo3_error("db error", true);
		exit;
	}
	if (is_array($menu_blocks)) {
		foreach ($menu_blocks as $block) {
			$path = $block["PATH"];
			//echo $path;
			/*
			if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==true) {		//провер€ем права
				$show_menu_blocks[$i] = $block;
				$i++;
			}
			*/
			$show_menu_blocks[$i] = $block;
			$i++;
		}
	}
	if ($menu_blocks == 0) {
		$show_menu_blocks = 0;
	}

	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', '/')==true) {
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}
	*/

	$superuser = true;
	$smarty->assign('superuser', $superuser);

	//show_ar($show_menu_blocks);
	$smarty->assign('menu_blocks', $show_menu_blocks);

	balo3_controller_output($smarty->fetch("$templates_path/menus/menus.tpl"));

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

/*
global $shedule_component;
if ($shedule_component==false) {
	exit;
}
*/
/*

$menu_blocks = list_menu_blocks();
$i = 1;
foreach ($menu_blocks as $block) {
	$path = $block["PATH"];
	//echo $path;
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $path)==true) {		//провер€ем права
		$show_menu_blocks[$i] = $block;
		$i++;
	}
}
if ($menu_blocks == 0) {
	$show_menu_blocks = 0;
}

if (db_check_rights(get_user_id(), 'MODIFY_MENU', '/')==true) {
	$superuser = true;
	$smarty->assign('superuser', $superuser);
}

//show_ar($show_menu_blocks);
$smarty->assign('menu_blocks', $show_menu_blocks);


	// выдаем шаблон
	out_main($smarty->fetch("menus/menus.tpl")); 
*/
?>