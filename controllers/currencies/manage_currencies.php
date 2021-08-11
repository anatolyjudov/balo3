<?php

require_once("$entrypoint_path/components/currencies/currencies_init.php");

do {

	if (!isset($_POST['send'])) {

		// גûהאול ראבכמם
		balo3_controller_output($smarty->fetch("$templates_path/currencies/manage_currencies.tpl"));
		break;

	}

	foreach(array_keys($_POST) as $k) {
		if (preg_match("/^cur(\d+)$/", $k, $matches)) {
			$currency_id = $matches[1];
			$equal = $_POST[$k];
			currencies_db_update_equal($currency_id, $equal);
		}
	}

	$currencies = currencies_db_get_currencies_list();
	if ($currencies == 'error') {
		balo3_error("DB error when init currencies component", true);
		echo "DB error when init currencies component";
		exit;
	}

	$smarty->assign("currencies_updated", true);

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/currencies/manage_currencies.tpl"));

} while (false);

?>