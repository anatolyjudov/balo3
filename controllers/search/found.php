<?php

require_once("$entrypoint_path/components/search/search_init.php");

do {

	$smarty->assign('search_areas', $search_areas);

	$search = addslashes($_GET["search"]);
	$smarty->assign('search', $search);

	if ($search == '') {
		balo3_controller_output($smarty->fetch("$templates_path/search/found.tpl")); 
	}

	//show_ar($_GET);

	// определим параметры постраничного вывода
	if (isset($_GET['page']) && preg_match("/^\d+$/", $_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$skip = ($page-1) * $results_on_page;
	$limit = $results_on_page;

	$smarty->assign('results_on_page', $results_on_page);

	$on_count = 0;
	foreach ($_GET as $key => $value) {
		if ($value == "on"){
			$on_count = $on_count + 1;
		}
	}

	//show_ar($_GET);
	//show_ar($search_areas);
	if(isset($_GET['all_areas']) and $_GET['all_areas'] == 'on') {

		foreach ($search_areas as $key => $value) {
			if($on_count > 1) {
				$results = call_user_func($search_areas[$key]["search_function"], $search);		// вызываем функцию поиска
			} elseif ($on_count==1) {
				$results = call_user_func($search_areas[$key]["search_function"], $search, $results_on_page, $skip);
			}
			if ($results === "error") {
				balo3_error("db error", true);
				exit;
			}
			$found[$key] = $results;
			$results = call_user_func($search_areas[$key]["count_function"], $search);
			if ($results === "error") {
				balo3_error("db error", true);
				exit;
			}
			$found_count[$key] = $results;
			$checked_areas[$key] = "on";
		}

	} else {

		foreach ($_GET as $key => $value) {
			if ($value == "on") {									// находим, какие области поиска указаны
				if (in_array($key, array_keys($search_areas))) {	// проверяем, есть ли такие области, или нас наебать хотят
					if($on_count > 1) {
						$results = call_user_func($search_areas[$key]["search_function"], $search, $max_area_results, 0);		// вызываем функцию поиска
					} elseif ($on_count == 1){
						$results = call_user_func($search_areas[$key]["search_function"], $search, $results_on_page, $skip);
					}
					if ($results === "error") {
						balo3_error("db error", true);
						exit;
					}
					$found[$key] = $results;
					$results = call_user_func($search_areas[$key]["count_function"], $search);
					if ($results === "error") {
						balo3_error("db error", true);
						exit;
					}
					$found_count[$key] = $results;
					$checked_areas[$key] = "on";
				}
			}
		}

	}

	$total_found = 0;
	foreach ($found_count as $area_count) {
		$total_found = $total_found + $area_count;
	}
	$smarty->assign('total_found', $total_found);

	$smarty->assign('found_count', $found_count);
	$smarty->assign('found', $found);
	$smarty->assign('on_count', $on_count);
	$smarty->assign_by_ref('checked_areas', $checked_areas);

	if ($on_count == 1){
		$smarty->assign('selected_area', $key);
		$pages_count = ceil($found_count[$search_areas[$key]["name_p"]]["count"] / $limit);
		$smarty->assign_by_ref("page", $page);
		$smarty->assign_by_ref("pages_count", $pages_count); 
		balo3_controller_output($smarty->fetch("$templates_path/search/found.tpl"));
		break;
	} else {
		$smarty->assign('max_area_results', $max_area_results);
		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/search/found.tpl")); 
		break;
	}

} while (false);

?>


<?php
/*
include($file_path."/includes/search/search_config.php");
include($file_path."/includes/search/search_db.php");
include($file_path."/includes/search/search_functions.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	$smarty->assign('search_areas', $search_areas);

	$search = addslashes($_GET["search"]);
	$smarty->assign('search', $search);

	if ($search == '') {
		out_main($smarty->fetch("search/found.tpl")); 
	}

	//show_ar($_GET);

	// определим параметры постраничного вывода
	if (isset($_GET['page']) && preg_match("/^\d+$/", $_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}

	$skip = ($page-1) * $results_on_page;
	$limit = $results_on_page;

	$smarty->assign('results_on_page', $results_on_page);

	$on_count = 0;
	foreach ($_GET as $key => $value) {
		if ($value == "on"){
			$on_count = $on_count + 1;
		}
	}

	//show_ar($_GET);
	//show_ar($search_areas);
	if(isset($_GET['all_areas']) and $_GET['all_areas'] == 'on') {

		foreach ($search_areas as $key => $value) {
			if($on_count > 1) {
				$results = call_user_func($search_areas[$key]["search_function"], $search);		// вызываем функцию поиска
			} elseif ($on_count==1) {
				$results = call_user_func($search_areas[$key]["search_function"], $search, $results_on_page, $skip);
			}
			if ($results === "error") {
				out_main($_ERRORS['DB_ERROR']);
				exit;
			}
			$found[$key] = $results;
			$results = call_user_func($search_areas[$key]["count_function"], $search);
			if ($results === "error") {
				out_main($_ERRORS['DB_ERROR']);
				exit;
			}
			$found_count[$key] = $results;
			$checked_areas[$key] = "on";
		}

	} else {

		foreach ($_GET as $key => $value) {
			if ($value == "on") {									// находим, какие области поиска указаны
				if (in_array($key, array_keys($search_areas))) {	// проверяем, есть ли такие области, или нас наебать хотят
					if($on_count > 1) {
						$results = call_user_func($search_areas[$key]["search_function"], $search, $max_area_results, 0);		// вызываем функцию поиска
					} elseif ($on_count == 1){
						$results = call_user_func($search_areas[$key]["search_function"], $search, $results_on_page, $skip);
					}
					if ($results === "error") {
						out_main($_ERRORS['DB_ERROR']);
						exit;
					}
					$found[$key] = $results;
					$results = call_user_func($search_areas[$key]["count_function"], $search);
					if ($results === "error") {
						out_main($_ERRORS['DB_ERROR']);
						exit;
					}
					$found_count[$key] = $results;
					$checked_areas[$key] = "on";
				}
			}
		}

	}

	$total_found = 0;
	foreach ($found_count as $area_count) {
		$total_found = $total_found + $area_count;
	}
	$smarty->assign('total_found', $total_found);
*/
	/*
	echo $total_found;
	show_ar($search_areas);
	show_ar($found_count);
	show_ar($found);
	*/
/*
	$smarty->assign('found_count', $found_count);
	$smarty->assign('found', $found);
	$smarty->assign('on_count', $on_count);
	$smarty->assign_by_ref('checked_areas', $checked_areas);

	if ($on_count == 1){
		$smarty->assign('selected_area', $key);
		//generate_pages_info($skip, $results_on_page, $found_count[$search_areas[$key]["name_p"]]["count"], count($found[$search_areas[$key]["name_p"]]));
		$pages_count = ceil($found_count[$search_areas[$key]["name_p"]]["count"] / $limit);
		$smarty->assign_by_ref("page", $page);
		$smarty->assign_by_ref("pages_count", $pages_count); 
		out_main($smarty->fetch("search/found.tpl"));
	} else {
		$smarty->assign('max_area_results', $max_area_results);
		// выдаем шаблон
		out_main($smarty->fetch("search/found.tpl")); 
	}

*/
?>
