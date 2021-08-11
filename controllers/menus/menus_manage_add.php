<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$block_id = $_POST["block_id"];
	$block_info = info_menu_block($block_id);
	$block_path = $block_info["PATH"];
	// проверяем права на управление пунктами
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $block_path)==false) {
		echo "У вас нет прав на добавление меню в этом блоке";
		exit;
	}
	*/

	$text = $_POST["link_text"];
	$address = $_POST["link_address"];
	$params = $_POST["params"];
	$sort = $_POST["sort"];
	$visible = $_POST["visible"];
	if (($text!="") or ($address!="")) {
		$add_item = add_menu_item ($block_id, $text, $address, $params, $sort, $visible);
		$smarty->clear_cache(null, 'modules|menus');
	}


	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback.tpl"));

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


$block_id = $_POST["block_id"];
$block_info = info_menu_block($block_id);
$block_path = $block_info["PATH"];
// проверяем права на управление пунктами
if (db_check_rights(get_user_id(), 'MODIFY_MENU', $block_path)==false) {
	echo "У вас нет прав на добавление меню в этом блоке";
	exit;
}

$text = $_POST["link_text"];
$address = $_POST["link_address"];
$params = $_POST["params"];
$sort = $_POST["sort"];
$visible = $_POST["visible"];
if (($text!="") or ($address!="")) {
	$add_item = add_menu_item ($block_id, $text, $address, $params, $sort, $visible);
	$smarty->clear_cache(null, 'modules|menus');
}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback.tpl")); 
*/
?>