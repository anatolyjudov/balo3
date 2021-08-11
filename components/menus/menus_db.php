<?php

function list_menu_blocks() {
	global $menus_blocks_table;
	global $ml_multilang_mode;

	$query = "select * from $menus_blocks_table order by PATH, ID asc";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}
	while ($row = mysql_fetch_array($result)) {
		$menu_blocks[$row['ID']] = $row;
	}
	if (mysql_num_rows($result)==0) {
		$menu_blocks = 0;
		return $menu_blocks;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($menu_blocks) > 0) ) {

		foreach($menu_blocks as &$menu_block) {
			unset($menu_block['TITLE']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($menu_blocks), 'menu_block');

		foreach($meta_strings as $block_id => $object_data) {
			foreach($object_data as $f => $v) {
				$menu_blocks[$block_id][$f] = $v;
			}
		}

	}

	return $menu_blocks;
}

function add_menu_block($title, $comment, $path, $image_foto_id = '') {
	global $menus_blocks_table;
	global $ml_multilang_mode;

	$comment = htmlspecialchars(addslashes($comment));
	$path = htmlspecialchars(addslashes($path));
	if ($image_foto_id != '') {
		$image_foto_id_val = "'$image_foto_id'";
	} else {
		$image_foto_id_val = "NULL";
	}

	if ($ml_multilang_mode) {

		$query = "insert into $menus_blocks_table (COMMENT, PATH, IMAGE_FOTO_ID) values ('$comment', '$path', $image_foto_id_val)";
		$result = balo3_db_query ($query);
		if (!$result) {
			return "error";
		}

		$block_id = mysql_insert_id();

		$status = db_ml_save_object_strings($block_id, 'menu_block', 
			array(
				'TITLE' => $title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$title = htmlspecialchars(addslashes($title));

		$query = "insert into $menus_blocks_table (TITLE, COMMENT, PATH, IMAGE_FOTO_ID) values ('$title', '$comment', '$path', $image_foto_id_val)";
		$result = balo3_db_query ($query);
		if (!$result) {
			return "error";
		}

	}

}

function info_menu_block($id) {
	global $menus_blocks_table;
	global $ml_multilang_mode;

	$query = "select * from $menus_blocks_table where ID='$id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	if ($return_data = mysql_fetch_array($result)) {
	} else {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		unset($return_data['TITLE']);
		$ml_info = db_ml_get_object_info($id, 'menu_block');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;
}

function modify_menu_block ($id, $title, $comment, $path,  $image_foto_id = '') {
	global $menus_blocks_table;
	global $ml_multilang_mode;
	if ($image_foto_id != '') {
		$image_foto_id_val = "'$image_foto_id'";
	} else {
		$image_foto_id_val = "NULL";
	}

	$comment = htmlspecialchars(addslashes($comment));
	$path = htmlspecialchars(addslashes($path));

	if ($ml_multilang_mode) {

		$query = "update
					$menus_blocks_table
				set
					COMMENT='$comment',
					PATH='$path',
					IMAGE_FOTO_ID = $image_foto_id_val
				where
					ID='$id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$status = db_ml_save_object_strings($id, 'menu_block', 
			array(
				'TITLE' => $title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$title = htmlspecialchars(addslashes($title));

		$query = "update 
					$menus_blocks_table
				set
					TITLE='$title',
					COMMENT='$comment',
					PATH='$path',
					IMAGE_FOTO_ID = $image_foto_id_val
				where
					ID='$id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

	}

	return "ok";
}

function remove_menu_block($id) {
	global $menus_blocks_table;
	global $menus_items_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		$query = "select ID from $menus_items_table where BLOCK_ID='$id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$item_ids = array();
		while($row = mysql_fetch_assoc($result)) {
			$item_ids[] = $row['ID'];
		}

		if (count($item_ids) > 0) {
			$status = db_ml_remove_object_info($item_ids, 'menu_item');
			if ($status === "error") {
				return "error";
			}
		}

		$status = db_ml_remove_object_info($id, 'menu_block');
		if ($status === "error") {
			return "error";
		}

	}

	$query = "delete from $menus_blocks_table where ID='$id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}
	
	$query = "delete from $menus_items_table where BLOCK_ID='$id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}
}

function module_show_menu_block($menu_block_id) {
	global $menus_blocks_table;
	global $ml_multilang_mode;

	$query = "select * from $menus_blocks_table where ID='$menu_block_id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}
	$return_data = mysql_fetch_array($result);
	if (mysql_num_rows($result)==0) {
		return 0;
	}

	if ($ml_multilang_mode) {
		unset($return_data['TITLE']);
		$ml_info = db_ml_get_object_info($menu_block_id, 'menu_block');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;
}

function list_menu_items ($block_id) {
	global $menus_items_table;
	global $ml_multilang_mode;

	$query = "select * from $menus_items_table where BLOCK_ID = '$block_id' order by SORT asc";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}
	while ($row = mysql_fetch_array($result)) {
		$list_menu_items[$row['ID']] = $row;
	}
	if (mysql_num_rows($result)==0) {
		$list_menu_items = 0;
		return $list_menu_items;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($list_menu_items) > 0) ) {

		foreach($list_menu_items as &$menu_item) {
			unset($menu_item['LINK_TEXT']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($list_menu_items), 'menu_item');

		foreach($meta_strings as $item_id => $object_data) {
			foreach($object_data as $f => $v) {
				$list_menu_items[$item_id][$f] = $v;
			}
		}

	}


	return $list_menu_items;
}

function add_menu_item ($block_id, $text, $address, $params, $sort, $visible) {
	global $menus_items_table;
	global $domain;
	global $root_path;
	global $ml_multilang_mode;

	$addr = htmlspecialchars(addslashes($address));
	$params = addslashes($params);
	$sort = addslashes($sort);

	if ($ml_multilang_mode) {

		$query = "insert into $menus_items_table (BLOCK_ID, LINK_ADDRESS, PARAMS, SORT, VISIBLE) 
			values ('$block_id', '$addr', '$params', '$sort', '$visible')";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$item_id = mysql_insert_id();

		$status = db_ml_save_object_strings($item_id, 'menu_item', 
			array(
				'LINK_TEXT' => $text
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$text = addslashes($text);
		$query = "insert into $menus_items_table (BLOCK_ID, LINK_TEXT, LINK_ADDRESS, PARAMS, SORT, VISIBLE) 
			values ('$block_id', '$text', '$addr', '$params', '$sort', '$visible')";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$item_id = mysql_insert_id();

	}

	return $item_id;
}

function modify_menu_item ($item_id, $text, $address, $params, $sort, $visible, $child_block_id) {
	global $menus_items_table;
	global $ml_multilang_mode;

	if (($child_block_id == 0) || ($child_block_id == "")) {
		$child_block_id = "NULL";
	}

	$address = htmlspecialchars(addslashes($address));
	$params = addslashes($params);
	$sort = addslashes($sort);

	if ($ml_multilang_mode) {

		$query = "update $menus_items_table set 
			LINK_ADDRESS='$address',
			PARAMS='$params',
			SORT='$sort',
			VISIBLE='$visible',
			CHILD_BLOCK_ID = $child_block_id
			where ID='$item_id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$status = db_ml_save_object_strings($item_id, 'menu_item', 
			array(
				'LINK_TEXT' => $text
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$text = addslashes($text);
		$query = "update $menus_items_table set 
			LINK_TEXT='$text',
			LINK_ADDRESS='$address',
			PARAMS='$params',
			SORT='$sort',
			VISIBLE='$visible'
			where ID='$item_id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

	}

	return "ok";
}

function delete_menu_item ($item_id) {
	global $menus_items_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($item_id, 'menu_item');
		if ($status === "error") {
			return "error";
		}

	}

	$query = "delete from $menus_items_table where ID='$item_id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	return "ok";
}

function show_block_items($block_id, $menu_access) {
	global $menus_items_table;
	global $domain;
	global $root_path;
	global $ml_multilang_mode;

	if ($menu_access == "swamp") {
		$query = "select * from $menus_items_table where BLOCK_ID='$block_id' and VISIBLE!='hidden' order by SORT asc";
	}else{
		$query = "select * from $menus_items_table where BLOCK_ID='$block_id' and VISIBLE='visible' order by SORT asc";
	}
	$result = balo3_db_query($query);
	while ($row = mysql_fetch_array($result)) {
		$block_items[$row['ID']] = $row;
		if (substr($block_items[$row['ID']]["LINK_ADDRESS"], 0, 1)=="/"){
			$block_items[$row['ID']]["LINK_ADDRESS"] = $domain.$root_path.$block_items[$row['ID']]["LINK_ADDRESS"];
		}
	}
	if (mysql_num_rows($result)==0) {
		$block_items = 0;
		return $block_items;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($block_items) > 0) ) {

		foreach($block_items as &$menu_item) {
			unset($block_items['LINK_TEXT']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($block_items), 'menu_item');

		foreach($meta_strings as $item_id => $object_data) {
			foreach($object_data as $f => $v) {
				$block_items[$item_id][$f] = $v;
			}
		}

	}

	return $block_items;
}

function db_menus_get_child_menus($child_block_ids, $menu_access) {
	global $menus_items_table;
	global $menus_blocks_table;
	global $domain;
	global $root_path;
	global $ml_multilang_mode;

	$query = "select * from $menus_blocks_table where ID in ($child_block_ids)";
	$res = balo3_db_query($query);
	if (!$res) {
		return array();
	}
	$blocks = array();
	while($row = mysql_fetch_assoc($res)) {
		$blocks['blocks'][$row['ID']] = $row;
	}

	if ($menu_access == "swamp") {
		$query = "select * from $menus_items_table where BLOCK_ID in ($child_block_ids) and VISIBLE!='hidden' order by SORT asc";
	}else{
		$query = "select * from $menus_items_table where BLOCK_ID in ($child_block_ids) and VISIBLE='visible' order by SORT asc";
	}
	$res = balo3_db_query($query);
	if (!$res) {
		return array();
	}

	while($row = mysql_fetch_assoc($res)) {
		$blocks['items'][$row['BLOCK_ID']][$row['ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($blocks) > 0) ) {

		$items_blocks = array();
		foreach($blocks['items'] as $block_id=>$block_items) {
			foreach($block_items as $item_id => &$item) {
				unset($item['LINK_TEXT']);
				$items_blocks[$item_id] = $block_id;
			}
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($items_blocks), 'menu_item');

		foreach($meta_strings as $item_id => $object_data) {
			foreach($object_data as $f => $v) {
				$blocks['items'][$items_blocks[$item_id]][$item_id][$f] = $v;
			}
		}

	}

	return $blocks;
}

?>