<?php

function currencies_db_get_currencies_list() {
	global $currencies_table;

	$q = "select * from $currencies_table";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$currencies = array();
	while($row = mysql_fetch_assoc($res)) {
		$currencies[$row['CURRENCY_ID']] = $row;
	}

	return $currencies;
}

function currencies_db_update_equal($currency_id, $equal) {
	global $currencies_table;

	$equal = addslashes($equal);
	$currency_id = addslashes($currency_id);

	$q = "update $currencies_table set EQUAL = '$equal' where CURRENCY_ID = $currency_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return 'ok';
}

?>