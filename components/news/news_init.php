<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/news/news_config.php");

/* функции базы данных компонента */
require("$components_path/news/news_db.php");

/* функции */
require("$components_path/news/news_functions.php");

multilang_load_smarty_config("news");

// подключать или не подключать компонент farch
$news_farch_component = true;

if ($news_farch_component && !isset($farch_component)) {

	/* инициализация компонента farch */
	require("$components_path/farch/farch_init.php");

	if (!isset($farch_component)) {

	balo3_error("news requires farch component", true);
	exit;

	}
}

$smarty->assign_by_ref("news_admin_branch", $menus_admin_branch);

$smarty->assign_by_ref("news_farch_component", $news_farch_component);

?>