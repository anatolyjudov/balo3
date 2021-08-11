<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$menu_blocks = list_menu_blocks();
	$smarty->assign_by_ref('menus_blocks', $menu_blocks);

	$block_id = $_GET["id"];
	$block_info = info_menu_block($block_id);
	$block_path = $block_info["PATH"];
	// проверяем права на управление пунктами
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $block_path)==false) {
		$error = "deny";
		out_main($smarty->fetch("menus/menus_manage.tpl"));
		exit;
	}
	*/

	$block_title = $block_info["TITLE"];
	$smarty->assign('block_title', $block_title);
	$smarty->assign('block_id', $block_id);

	$menu_items = list_menu_items($block_id);

	$smarty->assign('visibility', $menus_items_visibility);

	//show_ar($show_menu_blocks);
	$smarty->assign('menu_items', $menu_items);

	balo3_controller_output($smarty->fetch("$templates_path/menus/menus_manage.tpl"));

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

$menu_blocks = list_menu_blocks();
$smarty->assign_by_ref('menus_blocks', $menu_blocks);

$block_id = $_GET["id"];
$block_info = info_menu_block($block_id);
$block_path = $block_info["PATH"];
// проверяем права на управление пунктами
if (db_check_rights(get_user_id(), 'MODIFY_MENU', $block_path)==false) {
	$error = "deny";
	out_main($smarty->fetch("menus/menus_manage.tpl"));
	exit;
}

$block_title = $block_info["TITLE"];
$smarty->assign('block_title', $block_title);
$smarty->assign('block_id', $block_id);


$menu_items = list_menu_items($block_id);

$smarty->assign('visibility', $menus_items_visibility);


//show_ar($show_menu_blocks);
$smarty->assign('menu_items', $menu_items);


	// выдаем шаблон
	out_main($smarty->fetch("menus/menus_manage.tpl")); 
*/
?>