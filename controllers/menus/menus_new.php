<?php

require_once("$entrypoint_path/components/menus/menus_init.php");

do {

	$superuser = true;
	$smarty->assign_by_ref('superuser', $superuser);

	balo3_controller_output($smarty->fetch("$templates_path/menus/menus_new.tpl"));

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
	}else{
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}


	// выдаем шаблон
	out_main($smarty->fetch("menus/menus_new.tpl")); 
*/
?>