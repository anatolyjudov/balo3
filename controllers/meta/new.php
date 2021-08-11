<?php

require_once("$entrypoint_path/components/meta/meta_init.php");

if ($meta_farch_component) {
	require_once("$entrypoint_path/components/farch/farch_init.php");
}

do {


	$smarty->assign_by_ref('meta_error_states', $meta_error_states);
	$action = "new";

	if (isset($_POST['uri'])) {
		$action = "add";
	}

	if ($action == "undefined") {
		// обработка ошибки
		balo3_error("bad parameter", true);
		exit;
	}

	// если надо форму, то вот она
	if ($action == "new") {
		if (isset($_GET['uri'])) {
			$new_meta_info['URI'] = $_GET['uri'];
			$smarty->assign_by_ref("meta_info", $new_meta_info);
		}
		balo3_controller_output($smarty->fetch("$templates_path/meta/new.tpl"));
		break;
	}

	// принимаем параметры
	if (isset($_POST['uri'])) {
		$new_meta_info['URI'] = $_POST['uri'];
	} else {
		$new_meta_info['URI'] = "";
	}
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


	$smarty->assign_by_ref("meta_info", $new_meta_info);

	// проверяем корректность
	if ($new_meta_info['URI'] == "") {
		$smarty->assign("errmsg", $_META_ERRORS['BAD_URI']);
		balo3_controller_output($smarty->fetch("$templates_path/meta/new.tpl"));
		break;
	}

	$new_meta_info['URI'] = "/" . trim($new_meta_info['URI'],"/") . "/";
	if ($new_meta_info['URI'] == "//") $new_meta_info['URI'] = "/";

	// занят ли этот путь?
	if (meta_db_get_meta_id($new_meta_info['URI']) != "notfound") {
		$smarty->assign("errmsg", $_META_ERRORS['URI_EXISTS']);
		balo3_controller_output($smarty->fetch("$templates_path/meta/new.tpl"));
		break;
	}

	// проверим, можно ли тут добавлять
	if (!users_db_check_rights(users_get_user_id(), "CHANGE_METAINFO", $new_meta_info['URI'])) {
		$smarty->assign("errmsg", $_ERRORS['ACCESS_DENIED']);
		balo3_controller_output($smarty->fetch("$templates_path/meta/new.tpl"));
		break;
	}

	// добавляем
	if ($meta_farch_component) {
		$meta_id = meta_db_add_metainfo($new_meta_info['URI'], $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS'], $head_image_foto_id, $meta_image_foto_id);
	} else {
		$meta_id = meta_db_add_metainfo($new_meta_info['URI'], $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS']);
	}
	
	if ($new_meta_info === "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	$smarty->assign_by_ref("meta_id", $meta_id);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/meta/add.tpl"));

} while (false);

?>

<?php
/*
# new.php
# функции компонента META для добавления
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
	$action = "new";

	if (isset($_POST['uri'])) {
		$action = "add";
	}

	if ($action == "undefined") {
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// если надо форму, то вот она
	if ($action == "new") {
		if (isset($_GET['uri'])) {
			$new_meta_info['URI'] = $_GET['uri'];
			$smarty->assign_by_ref("meta_info", $new_meta_info);
		}
		out_main($smarty->fetch("meta/new.tpl"));
	}

	// принимаем параметры
	if (isset($_POST['uri'])) {
		$new_meta_info['URI'] = $_POST['uri'];
	} else {
		$new_meta_info['URI'] = "";
	}
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


	$smarty->assign_by_ref("meta_info", $new_meta_info);

	// проверяем корректность
	if ($new_meta_info['URI'] == "") {
		$smarty->assign("errmsg", $_META_ERRORS['BAD_URI']);
		out_main($smarty->fetch("meta/new.tpl"));
		exit;
	}

	$new_meta_info['URI'] = "/" . trim($new_meta_info['URI'],"/") . "/";
	if ($new_meta_info['URI'] == "//") $new_meta_info['URI'] = "/";

	// занят ли этот путь?
	if (meta_db_get_meta_id($new_meta_info['URI']) != "notfound") {
		$smarty->assign("errmsg", $_META_ERRORS['URI_EXISTS']);
		out_main($smarty->fetch("meta/new.tpl"));
		exit;
	}

	// проверим, можно ли тут добавлять
	if (!db_check_rights(get_user_id(), "CHANGE_METAINFO", $new_meta_info['URI'])) {
		$smarty->assign("errmsg", $_ERRORS['ACCESS_DENIED']);
		out_main($smarty->fetch("meta/new.tpl"));
		exit;
	}

	// добавляем
	if ($farch_component) {
		$meta_id = meta_db_add_metainfo($new_meta_info['URI'], $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS'], $head_image_foto_id, $meta_image_foto_id);
	} else {
		$meta_id = meta_db_add_metainfo($new_meta_info['URI'], $new_meta_info['TITLE'], $new_meta_info['INNER_TITLE'], $new_meta_info['KEYWORDS'], $new_meta_info['DESCRIPTION'], $new_meta_info['ERROR_STATE_LOCAL'], $new_meta_info['ERROR_STATE_INET'], $new_meta_info['OG_META_TAGS']);
	}
	
	if ($new_meta_info === "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	$smarty->assign_by_ref("meta_id", $meta_id);

	// выдаем все в шаблон
	out_main($smarty->fetch("meta/add.tpl"));
*/
?>