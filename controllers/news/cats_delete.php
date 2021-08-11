<?php

include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');

if(!db_check_rights(get_user_id(), 'MANAGE_NEWS'))
{
	out_error('ACCESS_DENIED');
	die();
}

global $smarty;

if(isset($_POST['id']) && isset($_POST['confirmed']))
{
	$result = db_news_cats_delete($_POST['id']);
	if($result != 'success')
	{
		die($_news_errors[$result]);
	}
	header('location:../..');
}
else
{
	$id = sa($_GET['id']);
	$smarty->assign("id", $id);
	$smarty->assign("cat", db_news_cats($id));
	out_main($smarty->fetch("news/cat_del_confirm.tpl"));
}

?>