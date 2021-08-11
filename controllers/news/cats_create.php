<?php

include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');

if(!db_check_rights(get_user_id(), 'MANAGE_NEWS'))
{
	out_error('ACCESS_DENIED');
	die();
}

if(!isset($_POST['new_uri']) || !isset($_POST['new_nam']))
{
	die($_news_errors['values_not_set']);
}

$result = db_news_cats_add($_POST['new_uri'], $_POST['new_nam']);
if($result != "success")
{
	die($_news_errors[$result]);
}
header('location:..');

?>