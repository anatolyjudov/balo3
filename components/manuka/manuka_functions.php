<?php

function manuka_get_node_info() {
	global $balo3_request_info;

	$parsed_url = parse_url($_SERVER['REQUEST_URI']);
	$parsed_url['path'] = rtrim($parsed_url['path'], "/") . "/";
	if (!isset($parsed_url['query'])) {
		$parsed_url['query'] = '';
	}

	$balo3_request_info['strpath'] = $parsed_url['path'];											// путь в виде строки
	$balo3_request_info['path'] = explode("/", trim($balo3_request_info['strpath'], "/"));			// путь в виде массива строк по уровням
	$balo3_request_info['query'] = $parsed_url['query'];											// строка get запроса

	$node_info = manuka_db_get_node_info($balo3_request_info['strpath']);
	if ("error" === $node_info) {
		balo3_error("db error when getting manuka node");
		return "error";
	}
	if ("notfound" === $node_info) {
		return "notfound";
	}

	return $node_info;
}


?>