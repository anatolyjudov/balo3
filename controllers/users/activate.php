<?
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;

	// логин
	if (isset($_GET['aid']) and $_GET['aid']!="" and preg_match("/\w+/", $_GET['aid'])) {
		$activate = db_users_activate_user($_GET['aid']);
		if ($activate == "error") {
			out_main($_ERRORS['DB_ERROR']);
		}  
		if ($activate == "notfound") {
			out_main($_ERRORS['BAD_PARAMETER']);
		} 				  
	} else {
		out_main($_ERRORS['BAD_PARAMETER']);
	}   
	
	// выдаем шаблон
	out_main($smarty->fetch("users/activate.tpl"));	
?>