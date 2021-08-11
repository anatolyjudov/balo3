<?php

function multilang_get_browser_language() {
	global $ml_langs_list, $ml_lang_default;

	return $ml_lang_default;

	// $_SERVER['HTTP_ACCEPT_LANGUAGE']
	// de,ru-ru;q=0.8,en;q=0.6,ru;q=0.4,en-us;q=0.2
	$lang_http_request = trim(current(explode("-", current(explode(",", current(explode(";", $_SERVER['HTTP_ACCEPT_LANGUAGE'])))))));

	//echo $lang_http_request;
	foreach($ml_langs_list as $lang_id => $lang) {
		if (isset($lang['link'])) {
			continue;
		}
		if ($lang['name_switcher'] == $lang_http_request) {
			$ml_lang_default = $lang_id;
		}
	}

	return $ml_lang_default;
}

function multilang_load_texts($component) {
	global $balo3_texts;
	global $ml_langs_list, $ml_current_language_id;
	global $components_path;

	$lang_switcher = $ml_langs_list[$ml_current_language_id]['name_switcher'];

	if (is_file($components_path . "/" . $component . "/" . $component . "_texts_" . $lang_switcher . ".php")) {
		$texts_file = $components_path . "/" . $component . "/" . $component . "_texts_" . $lang_switcher . ".php";
	} elseif (is_file($components_path . "/" . $component . "/" . "texts_" . $lang_switcher . ".php")) {
		$texts_file = $components_path . "/" . $component . "/" . "texts_" . $lang_switcher . ".php";
	} else {
		return false;
	}

	// подключаем массив с текстовыми константами
	balo3_load_texts($component, $texts_file);

}

function multilang_load_smarty_config($component) {
	global $balo3_texts;
	global $ml_langs_list, $ml_current_language_id;
	global $templates_path;

	$lang_switcher = $ml_langs_list[$ml_current_language_id]['name_switcher'];

	if (is_file($templates_path . "/" . $component . "/" . $component . "_texts_" . $lang_switcher . ".cfg")) {
		$config_file = $templates_path . "/" . $component . "/" . $component . "_texts_" . $lang_switcher . ".cfg";
	} elseif (is_file($templates_path . "/" . $component . "/" . "texts_" . $lang_switcher . ".cfg")) {
		$config_file = $templates_path . "/" . $component . "/" . "texts_" . $lang_switcher . ".cfg";
	} else {
		return false;
	}

	// подключаем конфиг смарти с текстовыми константами
	balo3_load_smarty_config($component, $config_file);

}

?>