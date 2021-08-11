<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/staticpages/staticpages_config.php");

/* функции базы данных компонента */
require("$components_path/staticpages/staticpages_db.php");

/* утилиты и другие функции компонента */
require("$components_path/staticpages/staticpages_functions.php");

if (!isset($manuka_nodes_table)) {

	balo3_error("staticpages requires manuka component", true);
	exit;

}

if (!isset($meta_table)) {

	balo3_error("staticpages requires meta component", true);
	exit;

}

// подключать или не подключать компонент farch
$staticpages_farch_component = true;
if ($staticpages_farch_component) {
	require_once("$entrypoint_path/components/farch/farch_init.php");
}
if ($staticpages_farch_component && (!isset($farch_component))) {
	balo3_error("staticpages requires farch component", true);
	exit;
}
$smarty->assign_by_ref("staticpages_farch_component", $staticpages_farch_component);

// подключение конфига для шаблонов
multilang_load_smarty_config("staticpages");

// админская ветка
$smarty->assign_by_ref("staticpages_admin_branch", $staticpages_admin_branch);


?>