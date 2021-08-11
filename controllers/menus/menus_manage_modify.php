<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$block_id = $_POST["block_id"];
	$block_info = info_menu_block($block_id);
	$block_path = $block_info["PATH"];
	// проверяем права на управление пунктами
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_MENU', $block_path)==false) {
		out_error($_ERRORS['ACCESS_DENIED']);
	}
	*/

	//show_ar($_POST);
	// изменения/удаление

	foreach(array_keys($_POST) as $k) {
		preg_match("/^(\d+)\,(\w+)$/", $k, $i);
		 $i = $i[1];
			$text = $_POST["$i,linktext"];
			$address = $_POST["$i,linkaddr"];
			$params = $_POST["$i,params"];
			$sort = $_POST["$i,sort"];
			$visible = $_POST["$i,visible"];
			$child_block_id = $_POST["$i,child_block_id"];
			$del = $_POST["$i,delete"];
			if ($del=="") {
				modify_menu_item ($i, $text, $address, $params, $sort, $visible, $child_block_id);
				$smarty->clear_cache(null, 'modules|menus');
			}else{
				delete_menu_item ($i);
				$smarty->clear_cache(null, 'modules|menus');
			}

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
	out_error($_ERRORS['ACCESS_DENIED']);
}

//show_ar($_POST);
// изменения/удаление
	foreach(array_keys($_POST) as $k) {
		preg_match("/^(\d+)\,(\w+)$/", $k, $i);
		 $i = $i[1];
			$text = $_POST["$i,linktext"];
			$address = $_POST["$i,linkaddr"];
			$params = $_POST["$i,params"];
			$sort = $_POST["$i,sort"];
			$visible = $_POST["$i,visible"];
			$child_block_id = $_POST["$i,child_block_id"];
			$del = $_POST["$i,delete"];
			if ($del=="") {
				modify_menu_item ($i, $text, $address, $params, $sort, $visible, $child_block_id);
				$smarty->clear_cache(null, 'modules|menus');
			}else{
				delete_menu_item ($i);
				$smarty->clear_cache(null, 'modules|menus');
			}
			


	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback.tpl")); 
*/
?>