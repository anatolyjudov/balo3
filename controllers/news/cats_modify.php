<?php

include($file_path.'/includes/news/news_config.php');
include($file_path.'/includes/news/news_db.php');

if(!db_check_rights(get_user_id(), 'MANAGE_NEWS'))
{
	out_error('ACCESS_DENIED');
	die();
}

$_cats = db_news_cats();
for($i = 0; $i < count($_cats); $i++)
{
	if(isset($_POST['uri'.$_cats[$i][0]]) && isset($_POST['nam'.$_cats[$i][0]]))
	{	
		$result = db_news_cats_edit($_cats[$i][2], $_cats[$i][0], $_POST['uri'.$_cats[$i][0]], $_POST['nam'.$_cats[$i][0]]);
		if($result != 'success')
		{
			die($_news_errors[$result].'<br>(категория '.$_cats[$i][0].' / "'.$_cats[$i][1].'")');
		}
	}
}

header('location:..');

?>