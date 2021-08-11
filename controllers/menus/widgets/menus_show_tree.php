<?php

function widget_menus_show_tree($widget_params, $smarty) {
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

	// находим вложенные меню
	$child_blocks_ids = array();
	foreach($show_block_items as $item) {
		if ( ($item['CHILD_BLOCK_ID'] != "") && ($item['CHILD_BLOCK_ID'] != 0) ) {
			$child_blocks_ids[$item['CHILD_BLOCK_ID']] = $item['CHILD_BLOCK_ID'];
		}
	}


	// если они есть - достанем всё про них
	if (count($child_blocks_ids) > 0) {
		$child_blocks_info = db_menus_get_child_menus(implode(",", array_keys($child_blocks_ids)), $menu_access);
		$child_blocks = $child_blocks_info['items'];
		$child_blocks_info = $child_blocks_info['blocks'];
	} else {
		$child_blocks = array();
	}

	//show_ar($child_blocks_info);
	//show_ar($child_blocks);

	$smarty->assign_by_ref("child_blocks_info", $child_blocks_info);
	$smarty->assign_by_ref("child_blocks", $child_blocks);
	$smarty->assign_by_ref("menu_info", $menu_block_info);

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
		if (count($child_blocks_info) > 0) {
			$image_foto_ids = array();
			foreach($child_blocks_info as &$child_block) {
				if (isset($child_block['IMAGE_FOTO_ID']) && ($child_block['IMAGE_FOTO_ID'] != '')) {
					$image_foto_ids[] = $child_block['IMAGE_FOTO_ID'];
				}
			}
			$child_block_images = far_db_get_fotos_info($image_foto_ids);
			if ($child_block_images === 'error') {
				return $_ERRORS['DB_ERROR'];
			}
			//show_ar($child_block_images); echo "<hr><hr>";
			foreach($child_blocks_info as &$child_block) {
				if (isset($child_block['IMAGE_FOTO_ID']) && ($child_block['IMAGE_FOTO_ID'] != '') && isset($child_block_images[$child_block['IMAGE_FOTO_ID']])) {
					$child_block['image'] = $child_block_images[$child_block['IMAGE_FOTO_ID']];
				}
			}
		}
	}

	/*
	show_ar($child_blocks_info);
	show_ar($child_blocks);
	*/


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




<?php

/*

# модуль отображает меню со всеми имеющимися подменю
function smarty_insert_menus_show_tree($args, $smarty) {
	global $_ERRORS;
	global $file_path;
	global $menus_template_dir;
	global $farch_component;

	include_once($file_path."/includes/menus/menus_config.php");
	include_once($file_path."/includes/menus/menus_db.php");
	
	//прием параметров
	$menu_block_id = $args["menu_block_id"];
	$menu_block_template = $args["menu_block_template"];
	$template = $menus_template_dir."/".$menu_block_template;
	$menu_cache = $args["menu_cache"];

	//узнаем, откуда пользователь
	$local_user = local_user();
	$user_id = get_user_id();
	if (($local_user==true) or ($user_id != -1)) {
		//$menu_access = "swamp";
		$menu_access = "notswamp";
		$usertype = "local";
	}else  {
		$usertype = "inet";
	}

	
	//кэш
	/*$smarty->caching = 0;
	$smarty->compile_check = false;*/
/*
	if ($menu_cache != "off") {
		$smarty->caching = 2;
		$smarty->cache_lifetime = $menus_cache_time;
		$smarty->compile_check = true;
		$cache_id = "modules|menus|$menu_block_id|$usertype";

        if ($smarty->is_cached($template, $cache_id)) {
			//echo "cached<br>";
            return $smarty->fetch($template, $cache_id);
        }
	}

	//вынимаем из базы блок
	$show_block_items = show_block_items($menu_block_id, $menu_access);

	$menu_block_info = info_menu_block($menu_block_id);

	//show_ar($show_block_items);
	//show_ar($menu_block_info);

	// находим вложенные меню
	$child_blocks_ids = array();
	foreach($show_block_items as $item) {
		if ( ($item['CHILD_BLOCK_ID'] != "") && ($item['CHILD_BLOCK_ID'] != 0) ) {
			$child_blocks_ids[$item['CHILD_BLOCK_ID']] = $item['CHILD_BLOCK_ID'];
		}
	}

	// если они есть - достанем всё про них
	if (count($child_blocks_ids) > 0) {
		$child_blocks_info = db_menus_get_child_menus(implode(",", array_keys($child_blocks_ids)), $menu_access);
		$child_blocks = $child_blocks_info['items'];
		$child_blocks_info = $child_blocks_info['blocks'];
	} else {
		$child_blocks = array();
	}

	//show_ar($child_blocks_info);
	//show_ar($child_blocks);

	$smarty->assign_by_ref("child_blocks_info", $child_blocks_info);
	$smarty->assign_by_ref("child_blocks", $child_blocks);
	$smarty->assign_by_ref("menu_info", $menu_block_info);

	if ($farch_component) {
		if (isset($menu_block_info['IMAGE_FOTO_ID']) && ($menu_block_info['IMAGE_FOTO_ID'] != '')) {
			$menu_block_info['image'] = far_db_get_foto_info($menu_block_info['IMAGE_FOTO_ID']);
			if ($menu_block_info['image'] == 'error') {
				return $_ERRORS['DB_ERROR'];
			}
			if ($menu_block_info['image'] == 'notfound') {
				$menu_block_info['image'] = array();
			}
		}
		if (count($child_blocks_info) > 0) {
			$image_foto_ids = array();
			foreach($child_blocks_info as &$child_block) {
				if (isset($child_block['IMAGE_FOTO_ID']) && ($child_block['IMAGE_FOTO_ID'] != '')) {
					$image_foto_ids[] = $child_block['IMAGE_FOTO_ID'];
				}
			}
			$child_block_images = far_db_get_fotos_info($image_foto_ids);
			if ($child_block_images === 'error') {
				return $_ERRORS['DB_ERROR'];
			}
			//show_ar($child_block_images); echo "<hr><hr>";
			foreach($child_blocks_info as &$child_block) {
				if (isset($child_block['IMAGE_FOTO_ID']) && ($child_block['IMAGE_FOTO_ID'] != '') && isset($child_block_images[$child_block['IMAGE_FOTO_ID']])) {
					$child_block['image'] = $child_block_images[$child_block['IMAGE_FOTO_ID']];
				}
			}
		}
	}

	//show_ar($child_blocks_info);

	if ($show_block_items == 0) {							// если в базе нет блока
		$smarty->assign('error', $error_no_menu);
		return $smarty->fetch('modules/menus_show.tpl');
	}elseif (!$smarty->template_exists ($template)){	//если не найден шаблон
		$smarty->assign('error', $error_no_template);
		return $smarty->fetch('modules/menus_show.tpl');
	}else{												//все нашли, выводим

		$smarty->assign('menu_items', $show_block_items);
		if ($menu_cache != "off") {
			return $smarty->fetch($template, $cache_id);
		} else {
			return $smarty->fetch($template);
		}
	}

}
*/

?>