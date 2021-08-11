<?php

require_once("$entrypoint_path/components/meta/meta_init.php");

if ($meta_farch_component) {
	require_once("$entrypoint_path/components/farch/farch_init.php");
}

do {

	$smarty->assign_by_ref('meta_error_states', $meta_error_states);

	if (isset($_GET['meta_id']) && preg_match("/^\d+$/", $_GET['meta_id'])) {
		$action = "edit";
		$meta_id = $_GET['meta_id'];
	}

	if (isset($_POST['meta_id']) && preg_match("/^\d+$/", $_POST['meta_id'])) {
		$action = "modify";
		$meta_id = $_POST['meta_id'];
	}

	if ($action == "undefined") {
		// обработка ошибки
		balo3_error("bad parameter", true);
		exit;
	}

	$smarty->assign_by_ref("meta_id", $meta_id);

	$old_meta_info = meta_db_get_metainfo_by_id($meta_id);
	if ($old_meta_info === "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($old_meta_info === "notfound") {
		// обработка ошибки
		balo3_error($_META_ERRORS['NO_SUCH_META'], true);
		exit;
	}

	if ($meta_farch_component) {
		if ($old_meta_info["HEAD_IMAGE_FOTO_ID"] != "") {
			$head_image_info = far_db_get_fotos_info($old_meta_info["HEAD_IMAGE_FOTO_ID"]);
			if ($head_image_info === "error") {
				// обработка ошибки
				balo3_error("db error", true);
				exit;
			}
			$old_meta_info['head_image'] = $head_image_info[key($head_image_info)];
		}
		if ($old_meta_info["META_IMAGE_FOTO_ID"] != "") {
			$meta_image_info = far_db_get_fotos_info($old_meta_info["META_IMAGE_FOTO_ID"]);
			if ($meta_image_info === "error") {
				// обработка ошибки
				balo3_error("db error", true);
				exit;
			}
			$old_meta_info['meta_image'] = $meta_image_info[key($meta_image_info)];
		}
	}

	// права е?
	if (!users_db_check_rights(users_get_user_id(), "CHANGE_METAINFO", $old_meta_info['URI'])) {
		balo3_error403();
		exit;
	}

	// если надо просто форму, то пожалуйста
	if ($action == "edit") {
		$smarty->assign_by_ref("meta_info", $old_meta_info);
		balo3_controller_output($smarty->fetch("$templates_path/meta/edit.tpl"));
		break;
	}

	$new_meta_info['META_ID'] = $old_meta_info['META_ID'];
	$new_meta_info['URI'] = $old_meta_info['URI'];
	$smarty->assign_by_ref("meta_info", $new_meta_info);

	// принимаем параметры
	if (isset($_POST['title'])) {
		$new_meta_info['TITLE'] = $_POST['title'];
	} else {
		$new_meta_info['TITLE'] = "";
	}
	if (isset($_POST['inner_title'])) {
		$new_meta_info['INNER_TITLE'] = $_POST['inner_title'];
	} else {
		$new_meta_info['INNER_TITLE'] = "";
	}
	if (isset($_POST['keywords'])) {
		$new_meta_info['KEYWORDS'] = $_POST['keywords'];
	} else {
		$new_meta_info['KEYWORDS'] = "";
	}
	if (isset($_POST['description'])) {
		$new_meta_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$new_meta_info['DESCRIPTION'] = "";
	}
	if (isset($_POST['og_meta_tags'])) {
		$new_meta_info['OG_META_TAGS'] = $_POST['og_meta_tags'];
	} else {
		$new_meta_info['OG_META_TAGS'] = "";
	}
	if (isset($_POST['error_state_local']) && preg_match("/^[\+-034noe]{3,4}$/", $_POST['error_state_local'])) {
		$new_meta_info['ERROR_STATE_LOCAL'] = $_POST['error_state_local'];
	} else {
		$new_meta_info['ERROR_STATE_LOCAL'] = "none";
	}
	if (isset($_POST['error_state_inet']) && preg_match("/^[\+-034noe]{3,4}$/", $_POST['error_state_inet'])) {
		$new_meta_info['ERROR_STATE_INET'] = $_POST['error_state_inet'];
	} else {
		$new_meta_info['ERROR_STATE_INET'] = "none";
	}
	if (preg_match("/^\d+$/", $_POST['meta_head_image_foto_id'])) {
		$head_image_foto_id = $_POST['meta_head_image_foto_id'];
	} else {
		$head_image_foto_id = "";
	}
	if (preg_match("/^\d+$/", $_POST['meta_image_foto_id'])) {
		$meta_image_foto_id = $_POST['meta_image_foto_id'];
	} else {
		$meta_image_foto_id = "";
	}

	// меняем
	if ($meta_farch_component) {
		$meta_id = meta_db_modify_metainfo($meta_id, $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS'], $head_image_foto_id, $meta_image_foto_id);
	} else {
		$meta_id = meta_db_modify_metainfo($meta_id, $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS']);
	}
	
	if ($new_meta_info === "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}


	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/meta/modify.tpl"));

} while (false);

?>



<?php
/*
# edit.php
# функции компонента META для редактирования
# используется правило CHANGE_METAINFO

include($file_path."/includes/meta/meta_config.php");
include($file_path."/includes/meta/meta_db.php");
include($file_path."/includes/meta/meta_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_META_ERRORS;
global $meta_error_states;

	$smarty->assign_by_ref('meta_error_states', $meta_error_states);

	if (isset($_GET['meta_id']) && preg_match("/^\d+$/", $_GET['meta_id'])) {
		$action = "edit";
		$meta_id = $_GET['meta_id'];
	}

	if (isset($_POST['meta_id']) && preg_match("/^\d+$/", $_POST['meta_id'])) {
		$action = "modify";
		$meta_id = $_POST['meta_id'];
	}

	if ($action == "undefined") {
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	$smarty->assign_by_ref("meta_id", $meta_id);

	$old_meta_info = meta_db_get_metainfo_by_id($meta_id);
	if ($old_meta_info === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($old_meta_info === "notfound") {
		echo $_META_ERRORS['NO_SUCH_META'];
		exit;
	}

	if ($farch_component) {
		if ($old_meta_info["HEAD_IMAGE_FOTO_ID"] != "") {
			$head_image_info = far_db_get_fotos_info($old_meta_info["HEAD_IMAGE_FOTO_ID"]);
			if ($head_image_info === "error") {
				echo $_ERRORS['DB_ERROR'];
				exit;
			}
			$old_meta_info['head_image'] = $head_image_info[key($head_image_info)];
		}
		if ($old_meta_info["META_IMAGE_FOTO_ID"] != "") {
			$meta_image_info = far_db_get_fotos_info($old_meta_info["META_IMAGE_FOTO_ID"]);
			if ($meta_image_info === "error") {
				echo $_ERRORS['DB_ERROR'];
				exit;
			}
			$old_meta_info['meta_image'] = $meta_image_info[key($meta_image_info)];
		}
	}

	// права е?
	if (!db_check_rights(get_user_id(), "CHANGE_METAINFO", $old_meta_info['URI'])) {
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// если надо просто форму, то пожалуйста
	if ($action == "edit") {
		$smarty->assign_by_ref("meta_info", $old_meta_info);
		out_main($smarty->fetch("meta/edit.tpl"));
	}

	$new_meta_info['META_ID'] = $old_meta_info['META_ID'];
	$new_meta_info['URI'] = $old_meta_info['URI'];
	$smarty->assign_by_ref("meta_info", $new_meta_info);

	// принимаем параметры
	if (isset($_POST['title'])) {
		$new_meta_info['TITLE'] = $_POST['title'];
	} else {
		$new_meta_info['TITLE'] = "";
	}
	if (isset($_POST['inner_title'])) {
		$new_meta_info['INNER_TITLE'] = $_POST['inner_title'];
	} else {
		$new_meta_info['INNER_TITLE'] = "";
	}
	if (isset($_POST['keywords'])) {
		$new_meta_info['KEYWORDS'] = $_POST['keywords'];
	} else {
		$new_meta_info['KEYWORDS'] = "";
	}
	if (isset($_POST['description'])) {
		$new_meta_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$new_meta_info['DESCRIPTION'] = "";
	}
	if (isset($_POST['og_meta_tags'])) {
		$new_meta_info['OG_META_TAGS'] = $_POST['og_meta_tags'];
	} else {
		$new_meta_info['OG_META_TAGS'] = "";
	}
	if (isset($_POST['error_state_local']) && preg_match("/^[\+-034noe]{3,4}$/", $_POST['error_state_local'])) {
		$new_meta_info['ERROR_STATE_LOCAL'] = $_POST['error_state_local'];
	} else {
		$new_meta_info['ERROR_STATE_LOCAL'] = "none";
	}
	if (isset($_POST['error_state_inet']) && preg_match("/^[\+-034noe]{3,4}$/", $_POST['error_state_inet'])) {
		$new_meta_info['ERROR_STATE_INET'] = $_POST['error_state_inet'];
	} else {
		$new_meta_info['ERROR_STATE_INET'] = "none";
	}
	if (preg_match("/^\d+$/", $_POST['meta_head_image_foto_id'])) {
		$head_image_foto_id = $_POST['meta_head_image_foto_id'];
	} else {
		$head_image_foto_id = "";
	}
	if (preg_match("/^\d+$/", $_POST['meta_image_foto_id'])) {
		$meta_image_foto_id = $_POST['meta_image_foto_id'];
	} else {
		$meta_image_foto_id = "";
	}

	// меняем
	if ($farch_component) {
		$meta_id = meta_db_modify_metainfo($meta_id, $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS'], $head_image_foto_id, $meta_image_foto_id);
	} else {
		$meta_id = meta_db_modify_metainfo($meta_id, $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS']);
	}
	
	if ($new_meta_info === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	// выдаем все в шаблон
	out_main($smarty->fetch("meta/modify.tpl"));
*/
?>