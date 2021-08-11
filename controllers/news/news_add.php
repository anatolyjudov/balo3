<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {


$smarty->assign_by_ref("news_picture_path", $news_picture_path);

if (isset($_GET['cid']) && preg_match("/^\d+$/", $_GET['cid'])) {
	$cid = $_GET['cid'];
}

$cats = news_db_get_all_cats();
$smarty->assign_by_ref("cats", $cats);

if(isset($_POST['confirmed']))
{

	$cid = news_sa($_POST['cid']);
	if(!users_db_check_rights(users_get_user_id(), 'MANAGE_NEWS', $cats[$cid][3] )) {
		balo3_error403();
		exit;
	}

	if ($farch_component) {
		if (preg_match("/^\d+$/", $_POST['news_image_foto_id'])) {
			$news_image_foto_id = $_POST['news_image_foto_id'];
		} else {
			$news_image_foto_id = "";
		}
		$result = news_db_create($_POST['name'], $_POST['cid'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture'], $news_image_foto_id);
	} else {
		$result = news_db_create($_POST['name'], $_POST['cid'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture']);
	}
	
	if($result != "success") {
		$smarty->assign("action", 'new/add');
		$smarty->assign_by_ref("name", $_POST['name']);
		$smarty->assign_by_ref("cid", $_POST['cid']);
		$smarty->assign_by_ref("text", $_POST['text']);
		$smarty->assign_by_ref("stext", $_POST['stext']);

		$smarty->assign("errmsg", $_news_errors[$result]);

		balo3_controller_output($smarty->fetch("$templates_path/news/news_form.tpl"));
		break;
	}

	header('location:../../');
}
else
{
	$smarty->assign("action", 'new/add');
	$smarty->assign("id", 0);
	$smarty->assign("stext", '');
	$smarty->assign("text", '');
	$smarty->assign("name", '');
	$smarty->assign_by_ref("cid", $cid);
	balo3_controller_output($smarty->fetch("$templates_path/news/news_form.tpl"));
	break;
}


} while (false);

?>


<?php
/*
include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');
include($file_path.'/includes/news/news_functions.php');

global $smarty;
global $news_picture_path;

$smarty->assign_by_ref("news_picture_path", $news_picture_path);

if (isset($_GET['cid']) && preg_match("/^\d+$/", $_GET['cid'])) {
	$cid = $_GET['cid'];
}

$cats = db_news_cats();
$smarty->assign_by_ref("cats", $cats);

if(isset($_POST['confirmed']))
{

	$cid = sa($_POST['cid']);
	if(!db_check_rights(get_user_id(), 'MANAGE_NEWS', $cats[$cid][3] )) {
		out_error('ACCESS_DENIED');
		die();
	}

	if ($farch_component) {
		if (preg_match("/^\d+$/", $_POST['news_image_foto_id'])) {
			$news_image_foto_id = $_POST['news_image_foto_id'];
		} else {
			$news_image_foto_id = "";
		}
		$result = db_news_create($_POST['name'], $_POST['cid'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture'], $news_image_foto_id);
	} else {
		$result = db_news_create($_POST['name'], $_POST['cid'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture']);
	}
	
	if($result != "success") {
		$smarty->assign("action", 'new/add');
		$smarty->assign_by_ref("name", $_POST['name']);
		$smarty->assign_by_ref("cid", $_POST['cid']);
		$smarty->assign_by_ref("text", $_POST['text']);
		$smarty->assign_by_ref("stext", $_POST['stext']);

		$smarty->assign("errmsg", $_news_errors[$result]);

		out_main($smarty->fetch('news/news_form.tpl'));
	}

	header('location:../../');
}
else
{
	$smarty->assign("action", 'new/add');
	$smarty->assign("id", 0);
	$smarty->assign("stext", '');
	$smarty->assign("text", '');
	$smarty->assign("name", '');
	$smarty->assign_by_ref("cid", $cid);
	out_main($smarty->fetch('news/news_form.tpl'));
}
*/
?>