<?
include_once($file_path."/includes/isetane/isetane_db.php");
include_once($file_path."/includes/isetane/isetane_config.php");
include_once($file_path."/includes/isetane/isetane_functions.php");
include_once($file_path."/includes/isetane/isetane_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	// все тэги для вывода
	$tags_list = istn_db_get_fototags(true);
	if ($tags_list === "error") {
		critical_error($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("tags_list", $tags_list);

	// принимаем данные для добавления
	if (isset($_POST['fototag_new'])) {
		$posted_info["FOTOTAG"] = $_POST['fototag_new'];
	} else {
		$posted_info["FOTOTAG"] = "";
	}
	if (isset($_POST['color_new'])) {
		$posted_info["COLOR"] = $_POST['color_new'];
	} else {
		$posted_info["COLOR"] = "";
	}
	if (isset($_POST['bgcolor_new'])) {
		$posted_info["BGCOLOR"] = $_POST['bgcolor_new'];
	} else {
		$posted_info["BGCOLOR"] = "";
	}
	if (isset($_POST['sort_value_new'])) {
		$posted_info["SORT_VALUE"] = $_POST['sort_value_new'];
	} else {
		$posted_info["SORT_VALUE"] = "";
	}

	$smarty->assign_by_ref("posted_info", $posted_info);

	// проверка входных данных
	if ($posted_info['FOTOTAG'] == "") {
		$smarty->assign("errmsg_add", $_ISTN_ERRORS['EMPTY_FOTOTAG_ADD']);
		out_main($smarty->fetch("isetane/tags_manage.tpl"));
	}
	if (!preg_match("/^[0-9abcdef]{0,6}$/", $posted_info['COLOR'])) {
		$smarty->assign("errmsg_add", $_ISTN_ERRORS['BAD_COLOR_FORMAT']);
		out_main($smarty->fetch("isetane/tags_manage.tpl"));
	}
	if (!preg_match("/^[0-9abcdef]{0,6}$/", $posted_info['BGCOLOR'])) {
		$smarty->assign("errmsg_add", $_ISTN_ERRORS['BAD_COLOR_FORMAT']);
		out_main($smarty->fetch("isetane/tags_manage.tpl"));
	}

	// добавляем
	$fototag_id = istn_db_add_fototag($posted_info['FOTOTAG'], $posted_info['COLOR'], $posted_info['BGCOLOR'], $posted_info['SORT_VALUE']);
	if ($fototag_id === "error") {
		$smarty->assign("errmsg_add", $_ISTN_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("isetane/tags_manage.tpl"));
	}
	$smarty->assign_by_ref("fototag_id", $fototag_id);

	out_main($smarty->fetch("isetane/tags_manage_add.tpl"));

?>