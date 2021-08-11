<?php

require_once("$entrypoint_path/components/settings/settings_init.php");

do {

	$old_settings_list = settings_db_get_settings_fullinfo();
	if ($old_settings_list === 'error') {
		balo3_error('db error', true);
		exit;
	}

	if (!isset($_POST['save'])) {
		$smarty->assign_by_ref('settings_list', $old_settings_list);
		balo3_controller_output($smarty->fetch("$templates_path/settings/settings_form.tpl"));
		break;
	}

	$new_settings_list = array();
	foreach($_POST as $k=>$v) {

		if(preg_match("/^setting/", $k)) {
			$key = substr($k, strlen('setting_'));
			if ($v != $old_settings_list[$key]['VALUE_STRING']) {
				$new_settings_list[$key] = $v;
			}
		}

	}

	if (count($new_settings_list) > 0) {

		$status = settings_db_save_settings($new_settings_list);
		if ("error" === $status) {
			balo3_error('db error', true);
			exit;
		}
	}

	balo3_controller_output($smarty->fetch("$templates_path/settings/settings_save.tpl"));

} while (false);

?>