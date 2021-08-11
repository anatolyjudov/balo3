<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/meta/meta_config.php");

/* функции базы данных компонента */
require("$components_path/meta/meta_db.php");

/* утилиты и другие функции компонента */
require("$components_path/meta/meta_functions.php");

multilang_load_smarty_config("meta");

	// ћ≈“ј»Ќ‘ќ–ћј÷»я ƒЋя “≈ ”ў≈√ќ URL
	$meta_info = meta_db_get_metainfo($balo3_request_info['strpath']);
	if ($meta_info == "error") {
		balo3_error("db error getting meta info", true);
		exit;
	}

	if ($meta_info != "notfound") {
		$smarty->assign_by_ref("meta_info", $meta_info);
		if (isset($meta_info['META_ID'])) $smarty->assign("meta_id", $meta_info['META_ID']);
		$smarty->assign("meta_title", $meta_info['TITLE']);
		$smarty->assign("meta_description", $meta_info['DESCRIPTION']);
		$smarty->assign("meta_keywords", $meta_info['KEYWORDS']);
		$smarty->assign("meta_og_meta_tags", $meta_info['OG_META_TAGS']);
	}

	// проверка на наличие права доступа через ошибки установленные метаинформацией
	if (meta_local_user()) {
		$meta_error_state = $meta_info['ERROR_STATE_LOCAL'];
	} else {
		$meta_error_state = $meta_info['ERROR_STATE_INET'];
	}
	if ($meta_error_state == "404") {
		balo3_error404();
		exit;
	}
	if ($meta_error_state == "403") {
		balo3_error403();
		exit;
	}

?>