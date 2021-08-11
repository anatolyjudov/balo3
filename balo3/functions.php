<?php

function balo3_output_headers() {

	# выдача HTTP-заголовка
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0", false);
	header("Cache-Control: max-age=0", false);
	header("Cache-Control: no-store", false);
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=utf-8");

}

/*
* Инициализация smarty
*/
function balo3_smarty_init() {
	global $smarty;
	global $templates_path, $smarty_tmp_path;

	$smarty->template_dir = "$templates_path/";
	$smarty->compile_dir = "$smarty_tmp_path/templates_c/";
	$smarty->config_dir = "$smarty_tmp_path/configs/";
	$smarty->cache_dir = "$smarty_tmp_path/cache/";

	return;
}

/*
* Анализ URL и определение текущей вершины
*/
function balo3_get_node_info() {
	global $common_manuka_nodes;
	global $balo3_request_info;
	global $smarty;

	$parsed_url = parse_url($_SERVER['REQUEST_URI']);
	$parsed_url['path'] = rtrim($parsed_url['path'], "/") . "/";
	if (!isset($parsed_url['query'])) {
		$parsed_url['query'] = '';
	}

	$balo3_request_info['strpath'] = $parsed_url['path'];								// путь в виде строки
	$balo3_request_info['path'] = explode("/", trim($balo3_request_info['strpath'], "/"));			// путь в виде массива строк по уровням
	$balo3_request_info['query'] = $parsed_url['query'];								// строка get запроса

	/* */
	$node_info = false;
	foreach($common_manuka_nodes as $m_id => $manuka_record) {
		if (isset($manuka_record['strict_path']) && ($manuka_record['strict_path'] != "")) {
			// проверяем по strict_path
			if ($manuka_record['strict_path'] == $balo3_request_info['strpath']) {
				$node_info = $manuka_record;
				break;
			}
		} elseif (isset($strict_path) && ($strict_path != "")) {
			// проверяем по regexp_path
		} else {
			continue;
		}
	}
	if ($node_info === false) {
		return "notfound";
	}

	return $node_info;
}

/*
* Вывод основного layout-шаблона в поток
*/
function balo3_process_layout() {
	global $smarty;
	global $balo3_node_info;
	global $templates_path;

	// дополнительные http-заголовки
	if (isset($balo3_node_info["custom_headers"]) && (count($balo3_node_info["custom_headers"]) > 0)) {
		foreach($balo3_node_info["custom_headers"] as $custom_header_info) {
			header($custom_header_info["header"], $custom_header_info["replace_flag"]);
		}
	}

	// обработка layout-шаблона шаблонизатором и вывод результата в поток
	$smarty->caching = false;
	$layout_filename = $templates_path . "/layouts/" . $balo3_node_info["layout"];
	if (!is_file($layout_filename)) {
		die("layout not found: $layout_filename");
	}
	$smarty->display($layout_filename);

	return;
}

/*
* Функция, сохраняющая результат работы контроллера в глобальном массиве
* Вызовом этой функции заканчивается нормальная работа контроллера
*/
function balo3_controller_output($output_content) {
	global $balo3_controllers_output, $controller_placeholder;

	$balo3_controllers_output[$controller_placeholder] = $output_content;

	return;
}

function balo3_error403() {

	if (function_exists("common_error403")) {

		common_error403();
		exit;

	} else {

		if (headers_sent()) {
			die ("403 Forbidden");
		} else {
			header("HTTP/1.0 403 Forbidden");
			die("403 Forbidden");
		}

	}
	exit;
}

function balo3_error404() {

	if (function_exists("common_error404")) {

		common_error404();
		exit;

	} else {

		if (headers_sent()) {
			die ("404 Page not found");
		} else {
			header("HTTP/1.0 404 Not Found");
			die("404 Not Found");
		}

	}
	exit;
}


function balo3_error($error_message, $fatal_error = false) {
	global $balo3_errors;
	global $balo3_fatal_error;

	$balo3_errors[] = _balo3_get_error_info_struct($error_message, $fatal_error);

	if (function_exists("common_error")) {
		return common_error($error_message, $fatal_error);
	}

	if ($fatal_error) {
		echo $error_message;
		exit;
	}

	return;

}

function balo3_load_texts($component, $texts_file = '') {
	global $balo3_texts;
	global $components_path;

	if ("" == $texts_file) {
		if (is_file($components_path . "/" . $component . "/" . $component . "_texts.php")) {
			$texts_file = $components_path . "/" . $component . "/" . $component . "_texts.php";
		} elseif (is_file($components_path . "/" . $component . "/" . "texts.php")) {
			$texts_file = $components_path . "/" . $component . "/" . "texts.php";
		} else {
			return false;
		}
	}

	$balo3_texts[$component] = include($texts_file);
	if ($balo3_texts[$component] === false) {
		unset($balo3_texts[$component]);
		balo3_error("error loading texts file");
		return false;
	}

	return true;

}

function balo3_load_smarty_config($component, $config_file = '') {
	global $smarty;
	global $templates_path;

	if ("" == $config_file) {
		if (is_file($templates_path . "/" . $component . "/" . $component . "_texts.cfg")) {
			$config_file = $templates_path . "/" . $component . "/" . $component . "_texts.cfg";
		} elseif (is_file($templates_path . "/" . $component . "/" . "texts.cfg")) {
			$config_file = $templates_path . "/" . $component . "/" . "texts.cfg";
		} else {
			return false;
		}
	}

	$smarty->config_load($config_file);

}

function balo3_json_out($result = array()) {
	echo json_encode($result);
	exit;
}

function balo3_text($component, $text_id) {
	global $balo3_texts;

	return $balo3_texts[$component][$text_id];
}

function _balo3_get_error_info_struct($error_message, $fatal_error) {

	return array(
			"error_message" => $error_message,
			"fatal_error" => $fatal_error,
			"created" => time()
		);

}

?>