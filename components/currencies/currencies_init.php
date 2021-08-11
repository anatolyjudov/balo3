<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/currencies/currencies_config.php");

/* функции для работы с бд */
require("$components_path/currencies/currencies_db.php");

$currencies = currencies_db_get_currencies_list();
if ($currencies == 'error') {
	balo3_error("DB error when init currencies component", true);
	echo "DB error when init currencies component";
	exit;
}

if (isset($smarty)) {
	$smarty->assign_by_ref("currencies", $currencies);
}



?>