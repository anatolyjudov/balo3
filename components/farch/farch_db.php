<?

function far_db_add_fototag($fototag, $color, $bgcolor, $sort_value = "") {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$fototag = addslashes($fototag);
	$color = addslashes($color);
	$bgcolor = addslashes($bgcolor);
	if ($sort_value != "") {
		$sort_value = addslashes($sort_value);
		$f_cl = ", SORT_VALUE";
		$v_cl = ", '$sort_value'";
	}

	$query = "insert into
				$farch_tags_table
				(FOTOTAG, COLOR, BGCOLOR $f_cl)
			values
				('$fototag', '$color', '$bgcolor' $v_cl)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return mysql_insert_id();
}

function far_db_update_fototag($fototag_id, $fototag, $color, $bgcolor, $sort_value) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$fototag = addslashes($fototag);
	$color = addslashes($color);
	$bgcolor = addslashes($bgcolor);
	$sort_value = addslashes($sort_value);

	$query = "update
				$farch_tags_table
			set
				FOTOTAG = '$fototag',
				COLOR = '$color',
				BGCOLOR = '$bgcolor',
				SORT_VALUE = '$sort_value'
			where
				FOTOTAG_ID = $fototag_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function far_db_remove_fototag($fototag_id) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$query = "delete from
				$farch_r_fotos_tags_table
			where
				FOTOTAG_ID = $fototag_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$query = "delete from
				$farch_tags_table
			where
				FOTOTAG_ID = $fototag_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function far_db_get_fototags($with_all_tag = false) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	if ($with_all_tag) {
		$where = "";
	} else {
		$where = " where FOTOTAG != 'all' ";
	}
	$query = "select
				t.*
			from
				$farch_tags_table t
			$where
			order by
				t.SORT_VALUE";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$tags = array();
	while($row = mysql_fetch_assoc($res)) {
		$tags[$row['FOTOTAG_ID']] = $row;
	}

	return $tags;
}

function far_db_create_foto_info($foto_title = "", $tech_info = "", $sort_value = "", $album_id = "") { 
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;

	$tech_info = addslashes(serialize($tech_info));
	if ($sort_value == "") {
		$sort_value = "0";
	}
	if ($album_id == '') {
		$album_id_value = 'NULL';
	} else {
		$album_id_value = "'$album_id'";
	}

	if ($ml_multilang_mode) {

		$query = "insert into
					$farch_fotos_table
				(FOTO_TITLE, TECH_INFO, SORT_VALUE, ALBUM_ID)
					values
				('', '$tech_info', '$sort_value', $album_id_value)";
		$res = balo3_db_query($query);
		if (!$res) {
			//echo "<pre>$query</pre>";
			return "error";
		}

		$foto_id = mysql_insert_id();

		$status = db_ml_save_object_strings($foto_id, 'farch_foto', 
			array(
				'FOTO_TITLE' => $foto_title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$foto_title = addslashes($foto_title);

		$query = "insert into
					$farch_fotos_table
				(FOTO_TITLE, TECH_INFO, SORT_VALUE, ALBUM_ID)
					values
				('$foto_title', '$tech_info', '$sort_value', $album_id_value)";
		$res = balo3_db_query($query);
		if (!$res) {
			//echo "<pre>$query</pre>";
			return "error";
		}

		$foto_id = mysql_insert_id();

	}


	return $foto_id;

}

function far_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "", $album_id = -1) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;


	$tech_info = addslashes(serialize($tech_info));
	if ($sort_value == "") {
		$sort_value = "0";
	}
	if ($album_id != -1) {
		if ($album_id == '') {
			$album_id_set = ", ALBUM_ID = NULL";
		} else {
			$album_id_set = ", ALBUM_ID = '$album_id'";
		}
	}

	if ($ml_multilang_mode) {

		$query = "update
					$farch_fotos_table
				set
					TECH_INFO = '$tech_info',
					SORT_VALUE = '$sort_value'
					$album_id_set
				where
					FOTO_ID = $foto_id";
		$res = balo3_db_query($query);
		if (!$res) {
			//echo "<pre>$query</pre>";
			return "error";
		}

		$status = db_ml_save_object_strings($foto_id, 'farch_foto', 
			array(
				'FOTO_TITLE' => $foto_title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$foto_title = addslashes($foto_title);

		$query = "update
					$farch_fotos_table
				set
					FOTO_TITLE = '$foto_title',
					TECH_INFO = '$tech_info',
					SORT_VALUE = '$sort_value'
					$album_id_set
				where
					FOTO_ID = $foto_id";
		$res = balo3_db_query($query);
		if (!$res) {
			//echo "<pre>$query</pre>";
			return "error";
		}


	}

	return "ok";
}

function far_db_set_fotos_album($album_id, $fotos_ids) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	if (count($fotos_ids) == 0) {
		return "ok";
	}

	if ($album_id == '') {
		$album_id_value = 'NULL';
	} else {
		$album_id_value = "'$album_id'";
	}

	$fotos_ids_str = implode(",", $fotos_ids);

	$q = "update $farch_fotos_table set ALBUM_ID = $album_id_value";
	$res = balo3_db_query($query);
	if (!$res) {
		//echo "<pre>$query</pre>";
		return "error";
	}

	return "ok";
}

function far_db_update_foto_title($foto_id, $foto_title) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		$status = db_ml_save_object_strings($foto_id, 'farch_foto', 
			array(
				'FOTO_TITLE' => $foto_title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$foto_title = addslashes($foto_title);
		$query = "update
					$farch_fotos_table
				set
					FOTO_TITLE = '$foto_title'
				where
					FOTO_ID = $foto_id";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

	}


	return "ok";
}

function far_db_remove_foto($foto_id) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;

	$foto_info = far_db_get_foto_info($foto_id);
	if ($foto_info === "error") {
		return "error";
	}
	if ($foto_info === "notfound") {
		return "notfound";
	}
	//show_ar($foto_info);
	farch_fotos_delete_foto_files($foto_info['ALBUM_ID'], array($foto_id=>$foto_info));

	$query = "delete from 
				$farch_fotos_table
			where
				FOTO_ID = $foto_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$query = "delete from 
				$farch_r_fotos_tags_table
			where
				FOTO_ID = $foto_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($foto_id, 'farch_foto');
		if ($status === "error") {
			return "error";
		}

	}

	return "ok";
}

function far_db_set_fototags_to_fotos($fototag_ids = array(), $foto_ids = array()) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$values_clause= "";
	foreach($fototag_ids as $fototag_id) {
		foreach($foto_ids as $foto_id) {
			$values_clause .= "($fototag_id, $foto_id),";
		}
	}
	if ($values_clause == "") {
		return "ok";
	}
	$values_clause = substr($values_clause, 0, -1);

	$query = "replace into $farch_r_fotos_tags_table (FOTOTAG_ID, FOTO_ID) values $values_clause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function far_db_clear_fototags_from_fotos($fototag_ids = array(), $foto_ids = array()) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$where_clause = "";
	foreach($fototag_ids as $fototag_id) {
		foreach($foto_ids as $foto_id) {
			$where_clause .= "(FOTOTAG_ID = $fototag_id and FOTO_ID = $foto_id) or ";
		}
	}
	if ($where_clause == "") {
		return "ok";
	}
	$where_clause = substr($where_clause, 0, -4);

	$query = "delete from $farch_r_fotos_tags_table where $where_clause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function far_db_get_fotos_fototags_status($fotos_ids = "") {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	if ($fotos_ids == "") {
		return array();
	}

	$fotos_count = count(explode(",", $fotos_ids));

	$query = "select
				t.FOTOTAG_ID, IF(count(rft.FOTO_ID) = 0, 'clear', if(count(rft.FOTO_ID) = $fotos_count, 'checked', 'mixed')) as FOTOTAG_STATE, count(rft.FOTO_ID) as FOTOTAG_FOTOS_COUNT
			from
				$farch_tags_table t
				left outer join $farch_r_fotos_tags_table rft on rft.FOTOTAG_ID = t.FOTOTAG_ID and rft.FOTO_ID in ($fotos_ids)
			group by
				t.FOTOTAG_ID";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$ffs = array();
	while($row = mysql_fetch_assoc($res)) {
		$ffs[$row['FOTOTAG_ID']] = $row;
	}

	return $ffs;
}

/*
function far_db_get_foto_info($foto_id) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$query = "select
				f.*
			from
				$farch_fotos_table f
			where
				FOTO_ID = $foto_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		return $row;
	}

	return "notfound";
}*/

function far_db_get_fotos_list($skip = 0, $limit = 0, $fototag_filter = array(), $albums_filter = array()) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;

	if ($skip != 0) {
		$limit_clause = "limit $skip, $limit";
	} elseif ($limit != 0) {
		$limit_clause = "limit $limit";
	} else {
		$limit_clause = "";
	}

	$whereclause = array();
	if (count($fototag_filter) > 0) {
		$fototag_filter_ids = implode(",", $fototag_filter);
		$whereclause[] = " rft.FOTOTAG_ID in ($fototag_filter_ids) ";
		$from_clause = "left outer join $farch_r_fotos_tags_table rft using (FOTO_ID)";
		$group_clause = "group by f.FOTO_ID";
	}

	if (count($albums_filter) > 0) {
		$albums_filter_str = implode(",", $albums_filter);
		$whereclause[] = " f.ALBUM_ID in ($albums_filter_str) ";
	}

	if (count($whereclause) > 0) {
		$whereclause_str = "where " . implode(" and ", $whereclause);
	}

	$query = "select
				f.*
			from
				$farch_fotos_table f
				$from_clause
			$whereclause_str
			$group_clause
			order by
				f.SORT_VALUE
			$limit_clause";
	$res = balo3_db_query($query);
	if (!$res) {
		//echo "<pre>$query</pre>";
		return "error";
	}

	$fotos_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		$fotos_list[$row['FOTO_ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($fotos_list) > 0) ) {

		foreach($fotos_list as &$foto) {
			unset($foto['FOTO_TITLE']);
		}

		$foto_strings = db_ml_get_objects_info(array_keys($fotos_list), 'farch_foto');

		foreach($foto_strings as $item_id => $object_data) {
			foreach($object_data as $f => $v) {
				$fotos_list[$item_id][$f] = $v;
			}
		}

	}

	return $fotos_list;
}

function far_db_get_fotos_fototags() {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	$query = "select 
				rft.FOTO_ID, rft.FOTOTAG_ID
			from
				$farch_r_fotos_tags_table rft";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$rfts = array();
	while($row = mysql_fetch_assoc($res)) {
		$rfts['fotos'][$row['FOTO_ID']]['tags'][$row['FOTOTAG_ID']] = $row['FOTOTAG_ID'];
		$rfts['tags'][$row['FOTOTAG_ID']]['fotos'][$row['FOTO_ID']] = $row['FOTO_ID'];
	}

	return $rfts;

}

function far_db_save_fotos_sort_values($fotos_sort_values) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;

	foreach($fotos_sort_values as $foto_id=>$sort_value) {
		if (!preg_match("/^\d+$/", $foto_id.$sort_value)) {
			return "error";
		}
		$query = "update $farch_fotos_table set SORT_VALUE = '$sort_value' where FOTO_ID = '$foto_id'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
	}

	return "ok";
}

function far_db_get_album_description_info($album_id) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;
	global $ml_multilang_mode;

	$query = "select
				a.ALBUM_ID, a.DESCRIPTION
			from
				$farch_albums_table a
			where
				a.ALBUM_ID = $album_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$return_data = $row;
	}

	if ($ml_multilang_mode) {
		unset($return_data['DESCRIPTION']);
		$album_data = db_ml_get_object_info($album_id, 'farch_album');
		if ($album_data == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $album_data);
	}


	return $return_data;
}

function far_db_get_albums($only_published = false) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;
	global $ml_multilang_mode;

	$whereclause = "";
	if ($only_published) {
		$whereclause = " where a.PUBLISHED = 1 ";
	}

	$query = "select
				a.ALBUM_ID, a.TREE_LEVEL, a.PARENT_ID, a.ALBUM_NAME, a.ALBUM_AUTHORS, a.SORT_VALUE, a.PICTURE_TECH_INFO, a.PUBLISHED
			from
				$farch_albums_table a
			$whereclause
			order by
				a.TREE_LEVEL, a.PARENT_ID, a.SORT_VALUE";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$albums_list = array();
	$albums_childs = array();
	$albums_parents = array();
	$albums_tree = array();
	$albums_plain = array();

	while($row = mysql_fetch_assoc($res)) {
		if ($row['PARENT_ID'] != '') {
			$albums_childs[$row['PARENT_ID']]['childs'][] = $row['ALBUM_ID'];
			$tmp = $albums_parents[$row['PARENT_ID']];
			$tmp[$albums_list[$row['PARENT_ID']]['TREE_LEVEL']] = $row['PARENT_ID'];
			$albums_parents[$row['ALBUM_ID']] = $tmp;
		} else {
			$albums_tree[$row['ALBUM_ID']] = array();
		}
		$row['PICTURE_TECH_INFO'] = unserialize($row['PICTURE_TECH_INFO']);
		$albums_list[$row['ALBUM_ID']] = $row;
	}

	foreach($albums_tree as $album_id=>&$album_subtree) {

		$top_childs = array();
		_save_childs($album_id, $album_subtree, $albums_childs, $albums_plain, $top_childs);

	}

	/*
	show_ar($albums_list);
	show_ar($albums_childs);
	show_ar($albums_parents);
	show_ar($albums_plain);
	show_ar($albums_tree);
	exit;
	*/

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($albums_list) > 0) ) {

		foreach($albums_list as &$album) {
			unset($album['ALBUM_NAME']);
			unset($album['DESCRIPTION']);
		}

		$album_datas = db_ml_get_objects_info(array_keys($albums_list), 'farch_album');

		foreach($album_datas as $album_id => $album_data) {
			foreach($album_data as $f => $v) {
				$albums_list[$album_id][$f] = $v;
			}
		}

	}

	return array(
			'list' => $albums_list,
			'childs' => $albums_childs,
			'parents' => $albums_parents,
			'tree' => $albums_tree,
			'plain' => $albums_plain);

}

function _save_childs($album_id, &$album_subtree, &$albums_childs, &$albums_plain, &$top_childs) {

	$albums_plain[] = $album_id;

	if (isset($albums_childs[$album_id]['childs']) && (count($albums_childs[$album_id]['childs']) > 0)) {

		$this_childs = array();
		foreach($albums_childs[$album_id]['childs'] as $child_album_id) {
			$album_subtree[$child_album_id] = array();
			$this_childs[] = $child_album_id;
			_save_childs($child_album_id, $album_subtree[$child_album_id], $albums_childs, $albums_plain, $this_childs);
		}
		$albums_childs[$album_id]['all_childs'] = $this_childs;
		$top_childs = array_merge($top_childs, $this_childs);
	}

	return;
}

function far_db_add_album($album_info) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;
	global $ml_multilang_mode;


	$album_authors = addslashes($album_info['ALBUM_AUTHORS']);
	$picture_tech_info = addslashes(serialize($album_info['PICTURE_TECH_INFO']));
	$published = $album_info['PUBLISHED'];

	// если указан парент - ищем его tree_level, иначе берем по нулям
	if (isset($album_info['PARENT_ID']) && ($album_info['PARENT_ID'] != 0)) {
		$parent_id = addslashes($album_info['PARENT_ID']);
		$query = "select TREE_LEVEL from $farch_albums_table where ALBUM_ID = $parent_id";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
		if ($row = mysql_fetch_assoc($res)) {
			$tree_level = $row['TREE_LEVEL'] + 1;
		}
	} else {
		$tree_level = 0;
		$parent_id = 'NULL';
	}

	// запрос-добавление
	if ($ml_multilang_mode) {

		$album_name = addslashes($album_info['ALBUM_NAME']);
		$description = addslashes($album_info['DESCRIPTION']);
		$query = "insert into 
					$farch_albums_table
				(PARENT_ID, TREE_LEVEL, ALBUM_NAME, ALBUM_AUTHORS, DESCRIPTION, PICTURE_TECH_INFO, PUBLISHED)
					values
				($parent_id, $tree_level, '', '$album_authors', '', '$picture_tech_info', '$published')";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$album_id = mysql_insert_id();

		$status = db_ml_save_object_strings($album_id, 'farch_album', 
			array(
				'ALBUM_NAME' => $album_name
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($album_id, 'farch_album',
			array(
				'DESCRIPTION' => $description
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$album_name = addslashes($album_info['ALBUM_NAME']);
		$description = addslashes($album_info['DESCRIPTION']);
		$query = "insert into 
					$farch_albums_table
				(PARENT_ID, TREE_LEVEL, ALBUM_NAME, ALBUM_AUTHORS, DESCRIPTION, PICTURE_TECH_INFO, PUBLISHED)
					values
				($parent_id, $tree_level, '$album_name', '$album_authors', '$description', '$picture_tech_info', '$published')";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$album_id = mysql_insert_id();
	}

	return $album_id;
}

function far_db_modify_album($album_id, $album_info) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;
	global $ml_multilang_mode;
	

	if (isset($album_info['ALBUM_NAME']) && (!$ml_multilang_mode)) {
		$set_clause[] = "ALBUM_NAME = '" . addslashes($album_info['ALBUM_NAME']) . "'";
	}
	if (isset($album_info['ALBUM_AUTHORS'])) {
		$set_clause[] = "ALBUM_AUTHORS = '" . addslashes($album_info['ALBUM_AUTHORS']) . "'";
	}
	if (isset($album_info['PICTURE_TECH_INFO'])) {
		$set_clause[] = "PICTURE_TECH_INFO = '" . addslashes(serialize($album_info['PICTURE_TECH_INFO'])) . "'";
	}
	if (isset($album_info['SORT_VALUE'])) {
		$set_clause[] = "SORT_VALUE = '" . addslashes($album_info['SORT_VALUE']) . "'";
	}
	if (isset($album_info['DESCRIPTION']) && (!$ml_multilang_mode)) {
		$set_clause[] = "DESCRIPTION = '" . addslashes($album_info['DESCRIPTION']) . "'";
	}
	if (isset($album_info['PUBLISHED'])) {
		$set_clause[] = "PUBLISHED = '" . addslashes($album_info['PUBLISHED']) . "'";
	}

	if (count($set_clause) == 0) {
		return "ok";
	}

	$set_clause_str = implode(',', $set_clause);

	// запрос-изменение
	$q = "update
			$farch_albums_table
		set
			$set_clause_str
		where
			ALBUM_ID = $album_id";

	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		if (isset($album_info['ALBUM_NAME'])) {
			$status = db_ml_save_object_strings($album_id, 'farch_album', 
				array(
					'ALBUM_NAME' => $album_info['ALBUM_NAME']
				));
			if ($status === "error") {
				return "error";
			}
		}

		if (isset($album_info['DESCRIPTION'])) {
			$status = db_ml_save_object_texts($album_id, 'farch_album',
				array(
					'DESCRIPTION' => $album_info['DESCRIPTION']
				));
			if ($status === "error") {
				return "error";
			}
		}

	}

	// разберемся теперь, что нам делать, если прислали парент айди
	if (isset($album_info['PARENT_ID'])) {

		if ($album_info['PARENT_ID'] == $album_id) {
			return "tree_error";
		}

		// достанем инфу по всем альбомам
		// нерационально конечно, но ерунда на самом деле
		$albums_info = far_db_get_albums();
		if ($albums_info === 'error') {
			return "error";
		}

		// проверим, изменилась ли фактическая инфа по родителю альбома
		// если изменилась, то нам надо корректно передвинуть альбом и все его вложенные альбомы
		if ($albums_info['list'][$album_id]['PARENT_ID'] != $album_info['PARENT_ID']) {

			// проверим, не является ли новый родительский альбом одним из вложенных альбомов
			if 
				( is_array($albums_info['childs'][$album_id]['all_childs'])
				&& in_array($album_info['PARENT_ID'], $albums_info['childs'][$album_id]['all_childs']) ) {
				return "tree_error";
			}

			// будем сохранять информацию о новом родительском альбоме
			if ($album_info['PARENT_ID'] != 0) {
				$new_parent_id = $album_info['PARENT_ID'];
				$new_tree_level = $albums_info['list'][$album_info['PARENT_ID']]['TREE_LEVEL']+1;
			} else {
				$new_parent_id = 'NULL';
				$new_tree_level = 0;
			}

			// обновим данные для альбома
			$q = "update
					$farch_albums_table
				set
					PARENT_ID = $new_parent_id,
					TREE_LEVEL = $new_tree_level
				where
					ALBUM_ID = $album_id";
			$res = balo3_db_query($q);
			if (!$res) {
				return "error";
			}

			// разберемся с его вложенными
			// уровень вложенности дерева изменился, насколько?
			$tree_level_diff = $new_tree_level - $albums_info['list'][$album_id]['TREE_LEVEL'];

			// если изменился, то изменим его для всех потомков альбома
			if ($tree_level_diff != 0) {
				if (isset($albums_info['childs'][$album_id]['all_childs']) && (count($albums_info['childs'][$album_id]['all_childs'])>0)) {

					$child_ids_str = implode(",", $albums_info['childs'][$album_id]['all_childs']);

					$q = "update
								$farch_albums_table
							set
								TREE_LEVEL = TREE_LEVEL + $tree_level_diff
							where
								ALBUM_ID in ($child_ids_str)";
					$res = balo3_db_query($q);
					if (!$res) {
						return "error";
					}

				}
			}
			
		}

	}
	

	return "ok";
}

function far_db_remove_album($album_id) {

	// нельзя просто так взять и удалить альбом
	// нужно найти все дочерние альбомы, и удалить их тоже
	// а поскольку к удалению альбома будут привязаные всякие усложнения, вроде удаления связанных сущностей,
	// то мы будем удалять вложенные альбомы рекурсивно

	// достанем инфу по всем альбомам
	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		return "error";
	}

	// запускаем удалятор
	$status = _far_db_remove_album_recursive($album_id, $albums_info);
	if ($status === 'error') {
		return "error";
	}

	// возврат
	return "ok";
}

function _far_db_remove_album_recursive($album_id, &$albums_info) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;
	global $ml_multilang_mode;

	// рекурсивный заворот на удаление вложенных альбомов
	if (isset($albums_info['childs'][$album_id]['childs']) && (count($albums_info['childs'][$album_id]['childs']) > 0)) {
		foreach($albums_info['childs'][$album_id]['childs'] as $child_album_id) {
			$status = _far_db_remove_album_recursive($child_album_id, $albums_info);
			if ($status === 'error') {
				return "error";
			}
		}
	}

	// теперь совершаем действия по удалению, собственно, альбома

	// получаем список фотографий в альбоме

	// удаляем файлы фотографий

	// удаляем записи в БД о фотографиях

	// удаляем привязки

	// удаляем запись в БД об альбоме
	$q = "delete from $farch_albums_table where ALBUM_ID = $album_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($album_id, 'farch_album');
		if ($status === "error") {
			return "error";
		}

	}

	return "ok";

}

function far_db_get_author_albums($author_id) {
	global $authors_table, $farch_rel_authors_table;
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_albums_table;

	$query = "select
				ALBUM_ID, AUTHOR_ID
			from
				$farch_rel_authors_table ra
				inner join $farch_albums_table a using (ALBUM_ID)
			where
				AUTHOR_ID = $author_id
				";
	$res = balo3_db_query($query);
	if (!$res) {
		//echo $query;
		return "error";
	}

	$rels = array();
	while($row = mysql_fetch_assoc($res)) {
		$rels[$row['ALBUM_ID']] = $row['ALBUM_ID'];
	}

	return $rels;
}

function far_db_get_albums_authors_rel($album_ids = array()) {
	global $authors_table, $farch_rel_authors_table;

	if (count($album_ids) == 0) {
		return array();
	}

	$album_ids_str = implode(",", $album_ids);

	$query = "select ALBUM_ID, AUTHOR_ID from $farch_rel_authors_table where ALBUM_ID in ($album_ids_str)";
	$res = balo3_db_query($query);
	if (!$res) {
		//echo $query;
		return "error";
	}

	$rels = array();
	while($row = mysql_fetch_assoc($res)) {
		$rels[$row['ALBUM_ID']][$row['AUTHOR_ID']] = $row;
	}

	return $rels;

}


function far_db_get_album_authors_rel($album_id) {
	global $authors_table, $farch_rel_authors_table;

	$query = "select ALBUM_ID, AUTHOR_ID from $farch_rel_authors_table where ALBUM_ID = $album_id";
	$res = balo3_db_query($query);
	if (!$res) {
		//echo $query;
		return "error";
	}

	$rels = array();
	while($row = mysql_fetch_assoc($res)) {
		$rels[$row['AUTHOR_ID']] = $row;
	}

	return $rels;

}

function far_db_set_album_author_rel($album_id, $author_id) {
	global $farch_rel_authors_table;

	$query = "replace into $farch_rel_authors_table (AUTHOR_ID, ALBUM_ID) values ($author_id, $album_id)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function far_db_remove_album_authors_rels($album_id) {
	global $farch_rel_authors_table;

	$query = "delete from $farch_rel_authors_table where ALBUM_ID = $album_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}




function far_db_get_fotos_info($fotos_ids) {
	global $farch_fotos_table, $farch_tags_table, $farch_r_fotos_tags_table;
	global $ml_multilang_mode;

	if (is_array($fotos_ids)) {
		foreach($fotos_ids as $k=>&$v) {
			if (!preg_match("/^\d+$/", $v)) {
				unset($fotos_ids[$k]);
			}
		}
		if (count($fotos_ids) == 0) {
			return array();
		}
		$fotos_ids_str = implode("," , $fotos_ids);
	} else {
		if (!preg_match("/^\d+$/", $fotos_ids)) {
			return array();
		}
		$fotos_ids_str = $fotos_ids;
	}

	$query = "select
				f.*
			from
				$farch_fotos_table f
			where
				FOTO_ID in ($fotos_ids_str)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$fotos_list = array();
	while ($row = mysql_fetch_assoc($res)) {
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		$fotos_list[$row['FOTO_ID']] = $row;
	}

	//show_ar($fotos_list);

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($fotos_list) > 0) ) {

		foreach($fotos_list as &$foto) {
			unset($foto['FOTO_TITLE']);
		}

		$foto_strings = db_ml_get_objects_info(array_keys($fotos_list), 'farch_foto');

		foreach($foto_strings as $item_id => $object_data) {
			foreach($object_data as $f => $v) {
				$fotos_list[$item_id][$f] = $v;
			}
		}
		
		reset($fotos_list);
	}
	//show_ar($fotos_list);
	return $fotos_list;
}

function far_db_get_foto_info($foto_id) {
	global $ml_multilang_mode;

	$fotos_list = far_db_get_fotos_info($foto_id);
	if ($fotos_list === 'error') {
		return "error";
	}
	if (!isset($fotos_list[$foto_id])) {
		return "notfound";
	}

	$return_data = $fotos_list[$foto_id];

	if ($ml_multilang_mode) {
		unset($return_data['FOTO_TITLE']);
		$foto_data = db_ml_get_object_info($foto_id, 'farch_foto');
		if ($foto_data == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $foto_data);
	}

	return $return_data;

}

?>