<?php

require_once("$entrypoint_path/components/search/search_init.php");

do {

	$smarty->assign('search_areas', $search_areas);

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/search/search.tpl"));

} while (false);

?>



<?php
/*
include($file_path."/includes/search/search_config.php");
include($file_path."/includes/search/search_db.php");

global $smarty;
global $params;
global $node_info;
global $_ERRORS;

	//show_ar($search_areas);
	$smarty->assign('search_areas', $search_areas);

	// ������ ������
	out_main($smarty->fetch("search/search.tpl")); 

*/
?>