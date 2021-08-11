<?php

require_once("$entrypoint_path/components/textblocks/textblocks_init.php");

do {

	$id = $_POST["id"];

	$info_textblock = info_textblock($id);
	if ($info_textblock === 'error') {
		balo3_error("db error", true);
		exit;
	}

	$path = $info_textblock["PATH"];
	/*
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==false) {		//проверяем права
		echo "У вас нет прав на редактирование этого блока.";
		exit;
	}
	*/

	$name = $_POST["name"];
	$title = $_POST["title"];
	$text = $_POST["text"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];
	if (isset($_POST["bgcolor"])) {
		$bgcolor = $_POST["bgcolor"];
	} else {
		$bgcolor = "";
	}
	if (isset($_POST['plainedit']) && ($_POST['plainedit'] == 'on')) {
		$plain_html_edit = 1;
	} else {
		$plain_html_edit = 0;
	}
	$modify_textblock = modify_textblock ($id, $title, $name, $text, $comment, $path, $bgcolor, $plain_html_edit);
	if ($modify_textblock === 'error') {
		balo3_error("db error", true);
		exit;
	}

	// очищаем кэщ
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

	$id = $_POST["id"];

	$info_textblock = info_textblock($id);
	if ($info_textblock === 'error') {
		out_error($_ERRORS['DB_ERROR']);
	}

	$path = $info_textblock["PATH"];
	if (db_check_rights(get_user_id(), 'MODIFY_TEXTBLOCK', $path)==false) {		//проверяем права
		echo "У вас нет прав на редактирование этого блока.";
		exit;
	}

	$name = $_POST["name"];
	$title = $_POST["title"];
	$text = $_POST["text"];
	$comment = $_POST["comment"];
	$path = $_POST["path"];
	$bgcolor = $_POST["bgcolor"];
	$modify_textblock = modify_textblock ($id, $title, $name, $text, $comment, $path, $bgcolor);
	if ($modify_textblock === 'error') {
		out_error($_ERRORS['DB_ERROR']);
	}

	// очищаем кэщ
	$smarty->clear_cache(null, 'modules|textblocks');


	// выдаем шаблон
	out_main($smarty->fetch("redirectback2.tpl")); 
*/
?>