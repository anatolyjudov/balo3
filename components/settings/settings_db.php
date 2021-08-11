<?php

function settings_db_get_settings_fullinfo($family = '') {
	global $settings_table;

	$whereclause = '';
	if ($family != '') {
		$family = addslashes($family);
		$whereclause = "where SETTING like '$family%'";
	}

	$query = "select * from $settings_table $whereclause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$settings = array();
	while($row = mysql_fetch_assoc($res)) {
		$settings[$row['SETTING']] = $row;
	}

	return $settings;
}

function settings_db_get_settings($family = '') {
	global $settings_table;

	$whereclause = '';
	if ($family != '') {
		$family = addslashes($family);
		$whereclause = "where SETTING like '$family%'";
	}

	$query = "select * from $settings_table $whereclause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$settings = array();
	while($row = mysql_fetch_assoc($res)) {
		$settings[$row['SETTING']] = $row['VALUE_STRING'];
	}

	return $settings;
}

function settings_db_save_settings($settings_values) {
	global $settings_table;

	if (!is_array($settings_values) || (count($settings_values) == 0)) {
		return "ok";
	}

	foreach($settings_values as $k=>$v) {

		$setting = addslashes($k);
		$val = addslashes($v);

		$query = "update
					$settings_table
				set
					VALUE_STRING = '$val'
				where
					SETTING = '$setting'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

	}

	return "ok";

}


?>