<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {

	$id = -1;

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} elseif (isset($_POST['id'])) {
		$id = $_POST['id'];
	}

	if($id === -1 || !is_numeric($id)) {
		balo3_error($_news_errors['error: id must be positive integer'], true);
	}

	$publisher_here = false;
	//это самый крутой модер новостей, главный редактор
	if(!users_db_check_rights(users_get_user_id(), 'NEWS_PUBLISHER', $node_info['current']['M_PATH_' . $node_info['current']['level']])){
		balo3_error403();
		exit;
	}

	$_news = news_db_get_one($id, true);
	if (!$_news){
		balo3_error($_news_errors['error'], true);
		exit;
	}
	if(isset($_POST['confirmed'])){
		news_db_public($id);
		header('location:../../');
	}else{
		$smarty->assign("news", $_news);
		balo3_controller_output($smarty->fetch("$templates_path/news/new_pub_confirm.tpl"));
	}

} while (false);

?>



<?php
/*
include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');

$id = -1;

if (isset($_GET['id'])) {
	$id = $_GET['id'];
} elseif (isset($_POST['id'])) {
	$id = $_POST['id'];
}

if($id === -1 || !is_numeric($id)) {
	die($_news_errors['error: id must be positive integer']);
}

$publisher_here = false;
//это самый крутой модер новостей, главный редактор
if(!db_check_rights(get_user_id(), 'NEWS_PUBLISHER', $node_info['current']['M_PATH_' . $node_info['current']['level']])){
	die($_news_errors['error: you not have permission level for published']);
}

$_news = db_news_one($id, true);
if (!$_news){
	die($_news_errors['error']);
}

global $smarty;


if(isset($_POST['confirmed'])){
	db_news_public($id);
	header('location:../../');
}else{
	$smarty->assign("news", $_news);
	out_main($smarty->fetch('news/new_pub_confirm.tpl'));
}
*/
?>