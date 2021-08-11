<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$id = $_GET["id"];
	$remove_textblock = remove_textblock($id);
	if ($remove_textblock === 'error') {
		balo3_error("db error", true);
		exit;
	}
	$smarty->clear_cache(null, 'modules|textblocks');

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback2.tpl"));

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
		echo "У вас нет прав на удаление этого блока.";
		exit;
	}

	$id = $_GET["id"];
	$remove_textblock = remove_textblock($id);
	if ($remove_textblock === 'error') {
		out_error($_ERRORS['DB_ERROR']);
	}
	$smarty->clear_cache(null, 'modules|textblocks');
	
	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>