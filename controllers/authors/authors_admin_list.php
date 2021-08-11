<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	$authors_list = authors_db_get_authors_list();
	if ($authors_list != "error") {
		$smarty->assign_by_ref("authors_list", $authors_list);
		//show_ar($authors_list);
	} else {
		$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
	}

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_list.tpl"));

} while (false);

?>