<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/menus/menus_config.php");

/* функции базы данных компонента */
require("$components_path/menus/menus_db.php");

multilang_load_smarty_config("menus");

// подключать или не подключать компонент farch
$menus_farch_component = false;

if ($menus_farch_component && (!isset($farch_component))) {

	balo3_error("menus requires farch component", true);
	exit;

}

$smarty->assign_by_ref("menus_admin_branch", $menus_admin_branch);

$smarty->assign_by_ref("menus_farch_component", $menus_farch_component);

?>