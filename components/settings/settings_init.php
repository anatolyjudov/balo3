<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/settings/settings_config.php");

/* функции базы данных компонента */
require("$components_path/settings/settings_db.php");

/* функции компонента */
require("$components_path/settings/settings_functions.php");


$smarty->assign_by_ref("settings_admin_branch", $settings_admin_branch);

?>