<?php

require_once("$entrypoint_path/components/feedback/feedback_init.php");

do {

	$subject_good_id = false;
	$subject_type = false;
	$smarty->assign_by_ref("subject_type", $subject_type);
	if (isset($_GET['ask']) && ctype_digit($_GET['ask'])) {
		$subject_good_id = $_GET['ask'];
		$subject_type = 'ask';
	}
	if (isset($_GET['buy']) && ctype_digit($_GET['buy'])) {
		$subject_good_id = $_GET['buy'];
		$subject_type = 'buy';
	}
	if (isset($_GET['discuss']) && ctype_digit($_GET['discuss'])) {
		$subject_good_id = $_GET['discuss'];
		$subject_type = 'discuss';
	}
	if ($subject_good_id !== false) {

		require_once("$entrypoint_path/components/catalog/catalog_init.php");
		$subject_good_info = catalog_db_get_good_info($subject_good_id);
		if ( ($subject_good_info !== "error") && ($subject_good_info !== "notfound") ) {
			$smarty->assign_by_ref("subject_good_info", $subject_good_info);
		}

	}

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/feedback/feedback_show_form.tpl"));

} while (false);

?>