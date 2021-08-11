<?php

function widget_menus_show($widget_params, $smarty) {
	global $components_path, $templates_path, $entrypoint_path;
	global $menus_blocks_table, $menus_items_table;

	include_once("$entrypoint_path/components/menus/menus_config.php");
	include_once("$entrypoint_path/components/menus/menus_db.php");

	// прием параметров
	$menu_block_id = $widget_params["menu_block_id"];
	$menu_block_template = $widget_params["menu_block_template"];
	$menu_cache = $widget_params["menu_cache"];

	$template = $templates_path . '/common/widgets/menus/' . $menu_block_template;

	//вынимаем из базы блок
	$show_block_items = show_block_items($menu_block_id, $menu_access);

	// show_ar($show_block_items);

	$menu_block_info = info_menu_block($menu_block_id);

	if ($menus_farch_component) {
		if (isset($menu_block_info['IMAGE_FOTO_ID']) && ($menu_block_info['IMAGE_FOTO_ID'] != '')) {
			$menu_block_info['image'] = far_db_get_foto_info($menu_block_info['IMAGE_FOTO_ID']);
			if ($menu_block_info['image'] == 'error') {
				return $_ERRORS['DB_ERROR'];
			}
			if ($menu_block_info['image'] == 'notfound') {
				$menu_block_info['image'] = array();
			}
		}
	}

	$smarty->assign_by_ref("menu_info", $menu_block_info);

	if ($show_block_items == 0) {							// если в базе нет блока
		$smarty->assign('error', $error_no_menu);
		return $smarty->fetch($templates_path . "/menus/menus_show.tpl");

	} elseif (!$smarty->template_exists ($template)){	//если не найден шаблон
		$smarty->assign('error', $error_no_template);
		return $smarty->fetch($templates_path . "/menus/menus_show.tpl");

	} else {											//все нашли, выводим

		$smarty->assign('menu_items', $show_block_items); 
		if ($menu_cache != "off") {
			return $smarty->fetch($template, $cache_id);
		} else {
			return $smarty->fetch($template);
		}
	}

}

?>