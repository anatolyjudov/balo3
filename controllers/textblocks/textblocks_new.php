<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$superuser = true;
	$smarty->assign('superuser', $superuser);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/textblocks/textblocks_new.tpl"));

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

	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', "/")==false) {		//проверяем права
		out_error($_ERRORS['ACCESS_DENIED']);
	}else{
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}

	// выдаем шаблон
	out_main($smarty->fetch("textblocks/textblocks_new.tpl")); 
*/
?>