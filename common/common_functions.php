<?php

function show_ar($arr) {

	echo "<pre>";
	print_r($arr);
	echo "</pre>";

}

function common_time_check($type, $action) {
	global $common_time_checks;

	$microtime = microtime(true);	// true for get_as_float

	if ($action == "start") {
		$common_time_checks[$type]["last_check"] = $microtime;
	}

	if ($action == "stop") {
		if ($common_time_checks[$type]["last_check"] == 0) {
			$common_time_checks[$type]["total"] = $microtime;
		} else {
			$common_time_checks[$type]["total"] += $microtime - $common_time_checks[$type]["last_check"];
		}
		$common_time_checks[$type]["checks"]++;
	}

}

function common_dump_time_checks() {
	global $common_time_checks;

	if (isset($_GET['time_checks'])) {
	
		foreach( $common_time_checks as $type=>$info) {
			echo "<b>$type</b><br>total: " . $info["total"] . " s total: " . $info["checks"] . " times<br>"; 
		}

	}

}

function common_error403() {
	global $smarty;
	global $templates_path, $root_path;
	global $balo3_errors;

	if (isset($_GET['debug'])) {
		$smarty->assign("common_console_log_errors", true);
	}

	$smarty->assign("balo3_errors", $balo3_errors);

	$smarty->assign("base_path", $root_path);
	$smarty->assign("templates_path", $templates_path);
	$smarty->assign("common_error_template", "403.tpl");

	$smarty->display("$templates_path/layouts/fatal_error.tpl");
	exit;

}

function common_error404() {
	global $smarty;
	global $templates_path, $root_path;
	global $balo3_errors;

	if (isset($_GET['debug'])) {
		$smarty->assign("common_console_log_errors", true);
	}

	$smarty->assign("balo3_errors", $balo3_errors);
	
	$smarty->assign("base_path", $root_path);
	$smarty->assign("templates_path", $templates_path);
	$smarty->assign("common_error_template", "404.tpl");

	$smarty->display("$templates_path/layouts/fatal_error.tpl");
	exit;
}

// функция, содержащая действия, которые мы производим при наступлении фатальной ошибки
// предполагается, что она будет вызываться только из контроллеров или _init файлов компонентов (из основного потока)
// функция собирает все накопленные ошибки, выводит их в шаблоне и завершает работу
//
// при необходимости ошибки можно вернуть в html-виде и не завершать работу
// например, если контроллер готов отдать управление следующему контроллеру на этой странице
function common_fatal_error($die = true) {
	global $smarty;
	global $root_path, $templates_path;
	global $balo3_errors;
	global $balo3_node_info;

	if (isset($_GET['debug'])) {
		$smarty->assign("common_console_log_errors", true);
	}

	// функцию обработки фатальной ошибки можно подменить на уровне manuka
	// это сделано для того, чтобы в определенных вершинах можно было предусмотреть вывод ошибок не в html, а в любом другом виде
	if (isset($balo3_node_info['replace_fatal_error_func']) && function_exists($balo3_node_info['replace_fatal_error_func'])) {

		// если функция найдена, уходим в неё
		return call_user_func($balo3_node_info['replace_fatal_error_func'], $die);

	} else {

		$smarty->assign("balo3_errors", $balo3_errors);

		if ($die) {
			$smarty->assign("base_path", $root_path);
			$smarty->assign("templates_path", $templates_path);
			$smarty->caching = false;
			$smarty->display("$templates_path/layouts/fatal_error.tpl");
			exit;
		} else {
			return $smarty->fetch("$templates_path/common/fatal_error.tpl");
		}

	}
}

function common_fatal_error_ajax($die = true) {
	global $smarty;
	global $root_path, $templates_path;
	global $balo3_errors;
	global $balo3_node_info;

	$errmsgs = array();
	foreach($balo3_errors as $balo3_error) {
		$errmsgs[] = $balo3_error['error_message'];
	}

	if ($die) {

		echo json_encode( array("state"=>"error", "errmsgs"=>$errmsgs) );
		exit;

	} else {

		return array("state"=>"error", "errmsgs"=>$errmsgs);

	}

}

function common_get_all_errmsgs() {
	global $balo3_errors;
	global $balo3_node_info;

	$errmsgs = array();
	foreach($balo3_errors as $balo3_error) {
		$errmsgs[] = $balo3_error['error_message'];
	}

	return $errmsgs;
}

// функция, создающая директории по заданному пути
function common_create_directories($path) {
	global $file_path;

	$t_path = substr($path, strlen($file_path));
	$dirs = explode("/", trim($t_path, "/ "));
	$current_dir = "";
	foreach ($dirs as $k=>$dirname) {
		$current_dir .= "$dirname/";
		if (!is_dir($file_path . "/" . $current_dir)) {
			mkdir($file_path . "/" . $current_dir);
		}
	}

	return "ok";
}


// генератор данных для паджинатора страниц
function common_generate_pages_info($skip, $items_on_pages, $results_count, $items_displayed) {
	global $smarty;

	// O O
	$from = $skip + 1;
	$to = $skip + $items_on_pages;
	$next_skip = $to;
	$prev_skip = $from - $items_on_pages - 1;
	if ($prev_skip < 0) {
		$prev_skip = 0;
	}
	if ($next_skip > $results_count) {
		$next_skip = $results_count;
	}
	if (($to - $from) >= $items_displayed) {
		$to = $from + $items_displayed - 1;
	}
	/*
	echo "<pre>";
		echo "skip: $skip\n";
	echo "shown_rows_from: $from\n";
		echo "shown_rows_to: $to\n";
	echo "shown_rows_prev_skip: $prev_skip\n";
		echo "shown_rows_next_skip: $next_skip\n";
	echo "results_count: $results_count\n";
		echo "</pre>";
	*/
	$smarty->assign("skip", $skip);
	$smarty->assign("shown_rows_from", $from);
	$smarty->assign("shown_rows_to", $to);
	$smarty->assign("shown_rows_prev_skip", $prev_skip);
	$smarty->assign("shown_rows_next_skip", $next_skip);
	$smarty->assign("results_count", $results_count);
	if ($items_displayed == $results_count) {
		$smarty->assign("shown_single_page", true);
	} else {
		$smarty->assign("shown_single_page", false);
	}

}


function common_save_active_elements() {
	global $users_session_info;

	if ( isset($_GET['elements']) && preg_match("/^[\d\s\w_\-]+$/", $_GET['elements']) ) {

		$session_data = json_decode($users_session_info['SESSION_DATA'], true);

		$session_data['active_elements'] = $_GET['elements'];

		$users_session_info['SESSION_DATA'] = json_encode($session_data);

		users_save_session_info();

	}

}

function common_get_active_elements() {
	global $users_session_info;

	$session_data = json_decode($users_session_info['SESSION_DATA'], true);

	return $session_data['active_elements'];

}

?>