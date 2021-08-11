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

	// обработка
	//show_ar($_POST);
	foreach(array_keys($_POST) as $k) {
		//echo $k;
		if (preg_match("/^fototag_(\d+)$/", $k, $matches)) {
			$fototag_id = $matches[1];
		} else {
			continue;
		}
		//echo $fototag_id;
		if (isset($_POST['delete_'.$fototag_id]) && ($_POST['delete_'.$fototag_id] == "on")) {
			$status = istn_db_remove_fototag($fototag_id);
			continue;
		}
		$fototag = $_POST[$k];
		$color = $_POST['color_'.$fototag_id];
		$bgcolor = $_POST['bgcolor_'.$fototag_id];
		$sort_value = $_POST['sort_value_'.$fototag_id];
		if ($fototag == "") continue;
		if (!preg_match("/^[0-9abcdef]{0,6}$/", $color)) continue;
		$status = istn_db_update_fototag($fototag_id, $fototag, $color, $bgcolor, $sort_value);
	}

	out_main($smarty->fetch("isetane/tags_manage_update.tpl"));
?>