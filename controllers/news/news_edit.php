<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {

	$smarty->assign_by_ref("news_picture_path", $news_picture_path);

	$id = -1;

	if (isset($_GET['id'])) {
		$id = news_sa($_GET['id']);
	} elseif (isset($_POST['id'])) {
		$id = news_sa($_POST['id']);
	}

	if($id === -1) {
		balo3_error('error: id must be positive integer', true);
		exit;
	}

	$_new = news_db_get_one($id,true);

	if(!users_db_check_rights(users_get_user_id(), 'MANAGE_NEWS', $_new[7] . $_new[0]))
	{
		balo3_error403();
		exit;
	}

	$_cats = news_db_get_all_cats();
	$smarty->assign_by_ref("cats", $_cats);
	$smarty->assign_by_ref("news_info", $_new);
	$smarty->assign_by_ref("npic", $_new[9]);

	$smarty->assign("action", 'edit/modify');

	if(isset($_POST['confirmed'])) {

		if ($news_farch_component) {
			if (preg_match("/^\d+$/", $_POST['news_image_foto_id'])) {
				$news_image_foto_id = $_POST['news_image_foto_id'];
			} else {
				$news_image_foto_id = "";
			}
			$result = news_db_edit($_POST['id'], $_POST['cid'], $_POST['name'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture'], $news_image_foto_id);
		} else {
			$result = news_db_edit($_POST['id'], $_POST['cid'], $_POST['name'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture']);
		}

		if($result != "success") {
			$smarty->assign_by_ref("name", $_POST['name']);
			$smarty->assign_by_ref("id", $_POST['id']);
			$smarty->assign_by_ref("cid", $_POST['cid']);
			$smarty->assign_by_ref("text", $_POST['text']);
			$smarty->assign_by_ref("stext", $_POST['stext']);
			$smarty->assign_by_ref("news_date", $_POST['news_date']);

			$smarty->assign("errmsg", $_news_errors[$result]);

			balo3_controller_output($smarty->fetch("$templates_path/news/news_form.tpl"));
			break;
		}

		header('location:../../');

	} else {

		$id = news_sa($_GET['id']);

		if ($news_farch_component) {

			if (isset($_new['news_image_foto_id']) && ($_new['news_image_foto_id'] != '')) {

				$_new['image'] = far_db_get_foto_info($_new['news_image_foto_id']);
				if ($_new['image'] == 'error') {
					balo3_error("db error", true);
					exit;
				}
				if ($_new['image'] == 'notfound') {
					$_new['image'] = array();
				}
			}

		}

		$smarty->assign_by_ref("id", $id);
		$smarty->assign_by_ref("stext", $_new['short_text']);
		$smarty->assign_by_ref("text", $_new['text']);
		$smarty->assign_by_ref("name", $_new['name']);
		$smarty->assign_by_ref("cid", $_new['category']);
		$smarty->assign_by_ref("news_image", $_new['image']);
		$smarty->assign_by_ref("news_date", $_new['posted']);

		//echo $_new[7] . $_new[0];
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

$id = -1;

if (isset($_GET['id'])) {
	$id = sa($_GET['id']);
} elseif (isset($_POST['id'])) {
	$id = sa($_POST['id']);
}

if($id === -1) {
	die($_news_errors['error: id must be positive integer']);
}

$_new = db_news_one($id,true);

//show_ar($_new);

if(!db_check_rights(get_user_id(), 'MANAGE_NEWS', $_new[7] . $_new[0]))
{
	out_error('ACCESS_DENIED');
	die();
}

$_cats = db_news_cats();
$smarty->assign_by_ref("cats", $_cats);
$smarty->assign_by_ref("news_info", $_new);
$smarty->assign_by_ref("npic", $_new[9]);

$smarty->assign("action", 'edit/modify');

if(isset($_POST['confirmed'])) {

	if ($farch_component) {
		if (preg_match("/^\d+$/", $_POST['news_image_foto_id'])) {
			$news_image_foto_id = $_POST['news_image_foto_id'];
		} else {
			$news_image_foto_id = "";
		}
		$result = db_news_edit($_POST['id'], $_POST['cid'], $_POST['name'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture'], $news_image_foto_id);
	} else {
		$result = db_news_edit($_POST['id'], $_POST['cid'], $_POST['name'], $_POST['text'], $_POST['stext'], $_POST['news_date'], $_POST['news_picture']);
	}

	if($result != "success") {
		$smarty->assign_by_ref("name", $_POST['name']);
		$smarty->assign_by_ref("id", $_POST['id']);
		$smarty->assign_by_ref("cid", $_POST['cid']);
		$smarty->assign_by_ref("text", $_POST['text']);
		$smarty->assign_by_ref("stext", $_POST['stext']);
		$smarty->assign_by_ref("news_date", $_POST['news_date']);

		$smarty->assign("errmsg", $_news_errors[$result]);

		out_main($smarty->fetch('news/news_form.tpl'));
	}

	header('location:../../');

} else {

	$id = sa($_GET['id']);

	if ($farch_component) {

		if (isset($_new['news_image_foto_id']) && ($_new['news_image_foto_id'] != '')) {

			$_new['image'] = far_db_get_foto_info($_new['news_image_foto_id']);
			if ($_new['image'] == 'error') {
				out_error($_ERRORS['DB_ERROR']);
			}
			if ($_new['image'] == 'notfound') {
				$_new['image'] = array();
			}
		}

	}

	$smarty->assign_by_ref("id", $id);
	$smarty->assign_by_ref("stext", $_new['short_text']);
	$smarty->assign_by_ref("text", $_new['text']);
	$smarty->assign_by_ref("name", $_new['name']);
	$smarty->assign_by_ref("cid", $_new['category']);
	$smarty->assign_by_ref("news_image", $_new['image']);
	$smarty->assign_by_ref("news_date", $_new['posted']);

	//echo $_new[7] . $_new[0];
	out_main($smarty->fetch('news/news_form.tpl'));

}
*/
?>