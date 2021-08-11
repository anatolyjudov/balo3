<?
include_once($file_path."/includes/isetane/isetane_db.php");
include_once($file_path."/includes/isetane/isetane_config.php");
include_once($file_path."/includes/isetane/isetane_functions.php");
include_once($file_path."/includes/isetane/isetane_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	$tags_list = istn_db_get_fototags(true);
	if ($tags_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("tags_list", $tags_list);

	out_main($smarty->fetch("isetane/tags_manage.tpl"));
?>