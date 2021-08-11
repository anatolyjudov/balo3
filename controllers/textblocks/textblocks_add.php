<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', "/")==false) {		//проверяем права
		echo "да у вас и прав-то нет.";
		exit;
	}
	*/

	$name = $_POST["name"];
	$title = $_POST["title"];
	$text = $_POST["text"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];
	$bgcolor = $_POST["bgcolor"];

	if (isset($_POST['plainedit']) && ($_POST['plainedit'] == 'on')) {
		$plain_html_edit = 1;
	} else {
		$plain_html_edit = 0;
	}

	$add_textblock = add_textblock($name, $title, $text, $comment, $path, $bgcolor, $plain_html_edit);
	if ($add_textblock === 'error') {
		balo3_error("db error", true);
		exit;
	}


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

	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', "/")==false) {		//проверяем права
		echo "да у вас и прав-то нет.";
		exit;
	}

	$name = $_POST["name"];
	$title = $_POST["title"];
	$text = $_POST["text"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];
	$bgcolor = $_POST["bgcolor"];
	$add_textblock = add_textblock($name, $title, $text, $comment, $path, $bgcolor);
	if ($add_textblock === 'error') {
		out_error($_ERRORS['DB_ERROR']);
	}

	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>