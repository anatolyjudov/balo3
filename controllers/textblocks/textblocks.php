<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$textblocks = list_textblocks();
	$i = 1;

	$show_textblocks = array();
	if (count($textblocks) != 0) {
		foreach ($textblocks as $block) {
			$path = $block["PATH"];
			//echo $path;
			/*
			if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==true) {		//проверяем права
				$show_textblocks[$i] = $block;
				$i++;
			}
			*/
			$show_textblocks[$i] = $block;
			$i++;
		}
	}

	/*
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', '/')==true) {
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}
	*/

	$superuser = true;
	$smarty->assign('superuser', $superuser);

	//show_ar($show_textblocks);
	$smarty->assign('textblocks', $show_textblocks);


	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/textblocks/textblocks.tpl"));

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

	$textblocks = list_textblocks();
	$i = 1;
	if (count($textblocks) != 0) {
		foreach ($textblocks as $block) {
			$path = $block["PATH"];
			//echo $path;
			if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==true) {		//проверяем права
				$show_textblocks[$i] = $block;
				$i++;
			}
		}
	}

	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', '/')==true) {
		$superuser = true;
		$smarty->assign('superuser', $superuser);
	}

	//show_ar($show_textblocks);
	$smarty->assign('textblocks', $show_textblocks);


	// выдаем шаблон
	out_main($smarty->fetch("textblocks/textblocks.tpl")); 
*/
?>