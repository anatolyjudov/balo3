<?php

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/currencies/currencies_config.php");

/* ������� ��� ������ � �� */
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