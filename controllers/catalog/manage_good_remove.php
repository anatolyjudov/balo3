<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {
	$l = count(explode("/", trim($catalog_admin_uri, "/")));
	$section_id = $balo3_request_info['path'][$l+1];
	$good_id = $balo3_request_info['path'][$l+4];
	if (!is_numeric($good_id)) {
		balo3_error('bad parameter', true);
		exit;
	}
	$smarty->assign_by_ref("section_id", $section_id);
	$smarty->assign_by_ref("good_id", $good_id);

	$old_good_info = catalog_db_get_good_info($good_id);
	if ($old_good_info === 'error') {
		balo3_error("db error", true);
		exit;
	}
	//$smarty->assign_by_ref("catalog_foto_params", $catalog_foto_params);

	if (!isset($_POST['confirm'])) {

		$smarty->assign_by_ref("good_info", $old_good_info);

		// גûהאול ראבכמם
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_delete.tpl"));
		break;
	}

	// הויסעגטו
	$status = catalog_db_remove_good($good_id);
	if ($status == 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_delete.tpl"));
		break;
	}

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/catalog/manage_good_remove.tpl"));

} while (false);

?>