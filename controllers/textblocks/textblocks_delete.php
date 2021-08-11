<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$id = $_GET["id"];
	$smarty->assign_by_ref('id', $id);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/textblocks/textblocks_delete.tpl"));

} while (false);

?>


<?php
/*
include($file_path."/includes/textblocks/textblocks_config.php");
include($file_path."/includes/textblocks/textblocks_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', '/')==false) {		//проверяем права
		out_error($_ERRORS['ACCESS_DENIED']);
	}

	$id = $_GET["id"];
	$smarty->assign('id', $id);

	// выдаем шаблон
	out_main($smarty->fetch("textblocks/textblocks_delete.tpl")); 
*/
?>