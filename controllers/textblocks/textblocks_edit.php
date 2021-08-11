<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$textblock_id = $_GET["id"];
	$info_textblock = info_textblock($textblock_id);
	if ($info_textblock === 'error') {
		balo3_error("db error", true);
		exit;
	}

	$path = $info_textblock["PATH"];
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==false) {		//провер€ем права
		echo "” вас нет прав на редактирование этого блока.";
		exit;
	}
	*/

	/*
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', '/')==true) {		//провер€ем права
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}
	*/

	$superuser = true;
	$smarty->assign('superuser', $superuser);

	$smarty->assign('info_textblock', $info_textblock);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/textblocks/textblocks_edit.tpl"));

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

	$textblock_id = $_GET["id"];
	$info_textblock = info_textblock($textblock_id);
	if ($info_textblock === 'error') {
		out_error($_ERRORS['DB_ERROR']);
	}

	$path = $info_textblock["PATH"];
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==false) {		//провер€ем права
		echo "” вас нет прав на редактирование этого блока.";
		exit;
	}

	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', '/')==true) {		//провер€ем права
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}

	$smarty->assign('info_textblock', $info_textblock);

	// выдаем шаблон
	out_main($smarty->fetch("textblocks/textblocks_edit.tpl")); 
*/
?>