<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$id = $_GET["id"];
	$remove_menu_block = remove_menu_block($id);

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

if (db_check_rights(get_user_id(), 'MODIFY_MENU', '/')==false) {		//проверяем права
	echo "У вас нет прав на удаление этого блока.";
	exit;
}

$id = $_GET["id"];
$remove_menu_block = remove_menu_block($id);
	
	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>