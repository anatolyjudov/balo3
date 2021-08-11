<?php

function settings_load($settings_family, $assign_to_smarty = false) {
	global $settings_list;

	if ($assign_to_smarty) {
		global $smarty;
	}

	$settings_values = settings_db_get_settings($settings_family);
	if ("error" === $settings_values) {
		return "error";
	}

	foreach($settings_values as $k=>$v) {
		global $$k;
		$$k = $v;
		if ($assign_to_smarty) {
			$smarty->assign($k, $v);
		}
		$settings_list[$k] = $v;
	}

	return "ok";
}


?>