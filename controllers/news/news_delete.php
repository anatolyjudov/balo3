<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {

	$id = -1;

	if (isset($_GET['id'])) {
		$id = news_sa($_GET['id']);
	} elseif (isset($_POST['id'])) {
		$id = news_sa($_POST['id']);
	}

	$_new = news_db_get_one($id,true);

	if(!users_db_check_rights(users_get_user_id(), 'MANAGE_NEWS', $_new[7] . $_new[0]))
	{
		balo3_error403();
		exit;
	}

	if($id === -1) {
		balo3_error($_news_errors['error: id must be positive integer'], true);
		exit;
	}

	if(isset($_POST['confirmed']))
	{
		$result = news_db_delete($_POST['id']);
		if($result != 'success')
		{
			balo3_error($_news_errors[$result], true);
			exit;
		}
		header('location:../../');
	}
	else
	{
		$smarty->assign_by_ref("news", $_new);
		$smarty->assign_by_ref("id", $id);
		balo3_controller_output($smarty->fetch("$templates_path/news/new_del_confirm.tpl"));
	}

} while (false);

?>



<?php
/*
include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');

$id = -1;

if (isset($_GET['id'])) {
	$id = sa($_GET['id']);
} elseif (isset($_POST['id'])) {
	$id = sa($_POST['id']);
}

$_new = db_news_one($id,true);

if(!db_check_rights(get_user_id(), 'MANAGE_NEWS', $_new[7] . $_new[0]))
{
	out_error('ACCESS_DENIED');
	die();
}

if($id === -1) {
	die($_news_errors['error: id must be positive integer']);
}

if(isset($_POST['confirmed']))
{
	$result = db_news_delete($_POST['id']);
	if($result != 'success')
	{
		die($_news_errors[$result]);
	}
	header('location:../../');
}
else
{
	$smarty->assign_by_ref("news", $_new);
	$smarty->assign_by_ref("id", $id);
	out_main($smarty->fetch('news/new_del_confirm.tpl'));
}
*/
?>