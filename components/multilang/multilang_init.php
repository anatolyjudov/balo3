<?php

/* программные настройки и глобальные переменные компонента */
require("$entrypoint_path/components/multilang/multilang_config.php");

/* функции базы данных компонента */
require("$entrypoint_path/components/multilang/multilang_db.php");

/* утилиты и другие функции компонента */
require("$entrypoint_path/components/multilang/multilang_functions.php");

$ml_lang_default = multilang_get_browser_language();
$ml_current_language_id = $ml_lang_default;

# langs switch by cookie
/*
if (isset($_GET['lang']) && isset($ml_langs_list[$_GET['lang']])) {
	$ml_current_language_id = $_GET['lang'];
	// поставим кукис
	setcookie($ml_lang_cookie_name, $ml_current_language_id, time() + $ml_cookie_lifetime, $root_path."/");
} elseif (isset($_COOKIE[$ml_lang_cookie_name]) && isset($ml_langs_list[$_COOKIE[$ml_lang_cookie_name]])) {
	$ml_current_language_id = $_COOKIE[$ml_lang_cookie_name];
	// продлим кукис
	setcookie($ml_lang_cookie_name, $ml_current_language_id, time() + $ml_cookie_lifetime, $root_path."/");
} else {
	$ml_current_language_id = $ml_lang_default;
}
*/
# langs switch by domain
foreach($ml_langs_list as $tmp_lang_id=>$lang_info) {
	if (in_array($common_simple_domain, $lang_info['domains'])) {
		$ml_current_language_id = $tmp_lang_id;
		break;
	}
}

$smarty->assign_by_ref("ml_multilang_mode", $ml_multilang_mode);
$smarty->assign_by_ref("ml_current_language_id", $ml_current_language_id);
$smarty->assign_by_ref("ml_langs_list", $ml_langs_list);
$smarty->assign("server_query_string", $_SERVER['QUERY_STRING']);

# переключимся на соответствующие шаблоны
$smarty->template_dir = $file_path . $ml_langs_list[$ml_current_language_id]['template_folder'];
$smarty->compile_dir = $ml_langs_list[$ml_current_language_id]['templates_c_folder'];

# подключим конфигурационный файл языка с сообщениями
multilang_load_texts("multilang");
multilang_load_smarty_config("multilang");


?>