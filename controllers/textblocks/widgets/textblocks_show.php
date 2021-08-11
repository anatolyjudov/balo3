<?php

function widget_textblocks_show($widget_params, $smarty) {
	global $components_path, $templates_path, $entrypoint_path;
	global $textblocks_table;

	include_once("$entrypoint_path/components/textblocks/textblocks_config.php");
	include_once("$entrypoint_path/components/textblocks/textblocks_db.php");

	//прием параметров
	if (isset($widget_params["textblock_id"])) {
		$textblock_id = $widget_params["textblock_id"];
	} else {
		$textblock_id = '';
	}
	if (isset($widget_params["textblock_name"])) {
		$textblock_name = $widget_params["textblock_name"];
	} else {
		$textblock_name = '';
	}
	$textblock_template = $widget_params["textblock_template"];

	$template = $templates_path . '/common/widgets/textblocks/' . $textblock_template;

	//кэш
	/*
	$smarty->caching = 2;
	$smarty->cache_lifetime = $textblocks_cache_time;
	$smarty->compile_check = true;
	$cache_id = "modules|textblocks|$textblock_id";
	if ($smarty->is_cached($template, $cache_id)) {
		return $smarty->fetch($template, $cache_id);
	}
	*/

	// вынимаем из базы блок
	if ($textblock_id != '') {
		$textblock_identify = $textblock_id;
	}
	if ($textblock_name != '') {
		$textblock_identify = $textblock_name;
	}
	$show_textblock = module_show_textblock($textblock_identify);

	if ($show_textblock == 'notfound') {							// если в базе нет блока
		$smarty->assign('error', $error_no_textblock);
		return $smarty->fetch($templates_path . '/textblocks/textblocks_show.tpl');
	} elseif (!$smarty->template_exists ($template)){	//если не найден шаблон
		$smarty->assign('error', $error_no_template);
		return $smarty->fetch($templates_path . '/textblocks/textblocks_show.tpl');
	} else {												//все нашли, выводим
			
		$title = $show_textblock["TITLE"];
		$text = $show_textblock["TEXT"];

		$smarty->assign('text', $text);
		$smarty->assign('title', $title);
		$smarty->assign('textblock_info', $show_textblock);
		//show_ar($show_textblock);
		return $smarty->fetch($template, $cache_id);
	}

}

?>