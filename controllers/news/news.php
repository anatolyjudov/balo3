<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {

	$page = news_sa($_GET['p']);
	$cat = news_sa($_GET['c']);
	$_news = news_db_get_all_news($page, $cat, true);

	//show_ar($_news);

	$publisher_here = false;

	//это самый крутой модер новостей, главный редактор
	if(users_db_check_rights(users_get_user_id(), 'NEWS_PUBLISHER', $balo3_node_info['NODE_PATH'])){
		$publisher_here = true;
	}

	global $smarty;
	$smarty->assign("publisher_here", $publisher_here);
	$smarty->assign("news", $_news);
	$smarty->assign("news_count", count($_news));
	$smarty->assign("news_per_page", $news_per_page);
	$smarty->assign("p", $page);
	if($cat)
	{
		$_cat = news_db_get_cats($cat);
		$smarty->assign("c", $cat);
		$smarty->assign("cat", $_cat[0]);
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/news/news_list_admin.tpl"));

} while (false);

?>