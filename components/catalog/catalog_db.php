<?php


function catalog_db_get_sections($only_published = false) {
	global $catalog_sections_table;
	global $ml_multilang_mode;

	$whereclause = "";
	if ($only_published) {
		$whereclause = " where a.PUBLISHED = 1 ";
	}

	$query = "select
				s.SECTION_ID, s.TREE_LEVEL, s.PARENT_ID, s.SECTION_NAME, s.SORT_VALUE, s.PICTURE_TECH_INFO, s.PUBLISHED, s.`DIRNAME`
			from
				$catalog_sections_table s
			$whereclause
			order by
				s.TREE_LEVEL, s.PARENT_ID, s.SORT_VALUE";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$sections_list = array();
	$sections_childs = array();
	$sections_parents = array();
	$sections_tree = array();
	$sections_plain = array();
	$sections_dirnames = array();

	while($row = mysql_fetch_assoc($res)) {
		if ($row['PARENT_ID'] != '') {
			$sections_childs[$row['PARENT_ID']]['childs'][] = $row['SECTION_ID'];
			$tmp = $sections_parents[$row['PARENT_ID']];
			$tmp[$sections_list[$row['PARENT_ID']]['TREE_LEVEL']] = $row['PARENT_ID'];
			$sections_parents[$row['SECTION_ID']] = $tmp;
		} else {
			$sections_tree[$row['SECTION_ID']] = array();
		}
		$row['PICTURE_TECH_INFO'] = unserialize($row['PICTURE_TECH_INFO']);
		$sections_list[$row['SECTION_ID']] = $row;
		$sections_dirnames[$row['SECTION_ID']] = $row['DIRNAME'];
	}

	foreach($sections_tree as $section_id=>&$section_subtree) {

		$top_childs = array();
		_catalog_save_childs($section_id, $section_subtree, $sections_childs, $sections_plain, $top_childs);

	}

	/*
	show_ar($sections_list);
	show_ar($sections_childs);
	show_ar($sections_parents);
	show_ar($sections_plain);
	show_ar($sections_tree);
	exit;
	*/

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($sections_list) > 0) ) {

		foreach($sections_list as &$album) {
			unset($album['SECTION_NAME']);
			unset($album['DESCRIPTION']);
		}

		$section_datas = db_ml_get_objects_info(array_keys($sections_list), 'catalog_section');

		foreach($section_datas as $section_id => $section_data) {
			foreach($section_data as $f => $v) {
				$sections_list[$section_id][$f] = $v;
			}
		}

	}

	return array(
			'list' => $sections_list,
			'childs' => $sections_childs,
			'parents' => $sections_parents,
			'tree' => $sections_tree,
			'plain' => $sections_plain,
			'dirnames' => $sections_dirnames
		);

}

function _catalog_save_childs($section_id, &$section_subtree, &$sections_childs, &$sections_plain, &$top_childs) {

	$sections_plain[] = $section_id;

	if (isset($sections_childs[$section_id]['childs']) && (count($sections_childs[$section_id]['childs']) > 0)) {

		$this_childs = array();
		foreach($sections_childs[$section_id]['childs'] as $child_section_id) {
			$section_subtree[$child_section_id] = array();
			$this_childs[] = $child_section_id;
			_catalog_save_childs($child_section_id, $section_subtree[$child_section_id], $sections_childs, $sections_plain, $this_childs);
		}
		$sections_childs[$section_id]['all_childs'] = $this_childs;
		$top_childs = array_merge($top_childs, $this_childs);
	}

	return;
}

function catalog_db_add_section($section_info) {
	global $catalog_sections_table;
	global $ml_multilang_mode;

	$picture_tech_info = addslashes(serialize($section_info['PICTURE_TECH_INFO']));
	$published = $section_info['PUBLISHED'];

	// если указан парент - ищем его tree_level, иначе берем по нулям
	if (isset($section_info['PARENT_ID']) && ($section_info['PARENT_ID'] != 0)) {
		$parent_id = addslashes($section_info['PARENT_ID']);
		$query = "select TREE_LEVEL from $catalog_sections_table where SECTION_ID = $parent_id";
		//echo "0<pre>$query</pre>";
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

		$section_name = addslashes($section_info['SECTION_NAME']);
		$description = addslashes($section_info['DESCRIPTION']);
		$dirname = addslashes($section_info['DIRNAME']);
		$query = "insert into 
					$catalog_sections_table
				(PARENT_ID, TREE_LEVEL, SECTION_NAME, DESCRIPTION, PICTURE_TECH_INFO, PUBLISHED, `DIRNAME`)
					values
				($parent_id, $tree_level, '', '', '$picture_tech_info', '$published', '$dirname')";
				//echo "1<pre>$query</pre>";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$section_id = balo3_db_last_insert_id();

		$status = db_ml_save_object_strings($section_id, 'catalog_section', 
			array(
				'SECTION_NAME' => $section_name
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($section_id, 'catalog_section',
			array(
				'DESCRIPTION' => $description
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$section_name = addslashes($section_info['SECTION_NAME']);
		$description = addslashes($section_info['DESCRIPTION']);
		$dirname = addslashes($section_info['DIRNAME']);
		$query = "insert into 
					$catalog_sections_table
				(PARENT_ID, TREE_LEVEL, SECTION_NAME, DESCRIPTION, PICTURE_TECH_INFO, PUBLISHED, `DIRNAME`)
					values
				($parent_id, $tree_level, '$section_name', '$description', '$picture_tech_info', '$published', '$dirname')";
					//echo "2<pre>$query</pre>";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$section_id = balo3_db_last_insert_id();
	}

//	var_dump($section_id);

	return $section_id;
}

function catalog_db_modify_section($section_id, $section_info) {
	global $catalog_sections_table;
	global $ml_multilang_mode;
	

	if (isset($section_info['SECTION_NAME']) && (!$ml_multilang_mode)) {
		$set_clause[] = "SECTION_NAME = '" . addslashes($section_info['SECTION_NAME']) . "'";
	}
	if (isset($section_info['PICTURE_TECH_INFO'])) {
		$set_clause[] = "PICTURE_TECH_INFO = '" . addslashes(serialize($section_info['PICTURE_TECH_INFO'])) . "'";
	}
	if (isset($section_info['SORT_VALUE'])) {
		$set_clause[] = "SORT_VALUE = '" . addslashes($section_info['SORT_VALUE']) . "'";
	}
	if (isset($section_info['DESCRIPTION']) && (!$ml_multilang_mode)) {
		$set_clause[] = "DESCRIPTION = '" . addslashes($section_info['DESCRIPTION']) . "'";
	}
	if (isset($section_info['PUBLISHED'])) {
		$set_clause[] = "PUBLISHED = '" . addslashes($section_info['PUBLISHED']) . "'";
	}
	if (isset($section_info['DIRNAME'])) {
		$set_clause[] = "DIRNAME = '" . addslashes($section_info['DIRNAME']) . "'";
	}

	if (count($set_clause) == 0) {
		return "ok";
	}

	$set_clause_str = implode(',', $set_clause);

	// запрос-изменение
	$q = "update
			$catalog_sections_table
		set
			$set_clause_str
		where
			SECTION_ID = $section_id";

	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		if (isset($section_info['SECTION_NAME'])) {
			$status = db_ml_save_object_strings($section_id, 'catalog_section', 
				array(
					'SECTION_NAME' => $section_info['SECTION_NAME']
				));
			if ($status === "error") {
				return "error";
			}
		}

		if (isset($section_info['DESCRIPTION'])) {
			$status = db_ml_save_object_texts($section_id, 'catalog_section',
				array(
					'DESCRIPTION' => $section_info['DESCRIPTION']
				));
			if ($status === "error") {
				return "error";
			}
		}

	}

	// разберемся теперь, что нам делать, если прислали парент айди
	if (isset($section_info['PARENT_ID'])) {

		if ($section_info['PARENT_ID'] == $section_id) {
			return "tree_error";
		}

		// достанем инфу по всем секциям
		// нерационально конечно, но ерунда на самом деле
		$sections_info = catalog_db_get_sections();
		if ($sections_info === 'error') {
			return "error";
		}

		// проверим, изменилась ли фактическая инфа по родителю секции
		// если изменилась, то нам надо корректно передвинуть секция и все его вложенные секции
		if ($sections_info['list'][$section_id]['PARENT_ID'] != $section_info['PARENT_ID']) {

			// проверим, не является ли новый родительский секция одним из вложенных секций
			if 
				( is_array($sections_info['childs'][$section_id]['all_childs'])
				&& in_array($section_info['PARENT_ID'], $sections_info['childs'][$section_id]['all_childs']) ) {
				return "tree_error";
			}

			// будем сохранять информацию о новой родительской секции
			if ($section_info['PARENT_ID'] != 0) {
				$new_parent_id = $section_info['PARENT_ID'];
				$new_tree_level = $sections_info['list'][$section_info['PARENT_ID']]['TREE_LEVEL']+1;
			} else {
				$new_parent_id = 'NULL';
				$new_tree_level = 0;
			}

			// обновим данные для секции
			$q = "update
					$catalog_sections_table
				set
					PARENT_ID = $new_parent_id,
					TREE_LEVEL = $new_tree_level
				where
					SECTION_ID = $section_id";
			$res = balo3_db_query($q);
			if (!$res) {
				return "error";
			}

			// разберемся с его вложенными
			// уровень вложенности дерева изменился, насколько?
			$tree_level_diff = $new_tree_level - $sections_info['list'][$section_id]['TREE_LEVEL'];

			// если изменился, то изменим его для всех потомков секции
			if ($tree_level_diff != 0) {
				if (isset($sections_info['childs'][$section_id]['all_childs']) && (count($sections_info['childs'][$section_id]['all_childs'])>0)) {

					$child_ids_str = implode(",", $sections_info['childs'][$section_id]['all_childs']);

					$q = "update
								$catalog_sections_table
							set
								TREE_LEVEL = TREE_LEVEL + $tree_level_diff
							where
								SECTION_ID in ($child_ids_str)";
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

function catalog_db_remove_section($section_id) {

	// нельзя просто так взять и удалить секцию
	// нужно найти все дочерние секции, и удалить их тоже
	// а поскольку к удалению секции будут привязаные всякие усложнения, вроде удаления связанных сущностей,
	// то мы будем удалять вложенные секции рекурсивно

	// достанем инфу по всем секциям
	$sections_info = catalog_db_get_sections();
	if ($sections_info === 'error') {
		return "error";
	}

	// запускаем удалятор
	$status = _catalog_db_remove_section_recursive($section_id, $sections_info);
	if ($status === 'error') {
		return "error";
	}

	// возврат
	return "ok";
}

function _catalog_db_remove_section_recursive($section_id, &$sections_info) {
	global $catalog_sections_table;
	global $ml_multilang_mode;

	// рекурсивный заворот на удаление вложенных секций
	if (isset($sections_info['childs'][$section_id]['childs']) && (count($sections_info['childs'][$section_id]['childs']) > 0)) {
		foreach($sections_info['childs'][$section_id]['childs'] as $child_section_id) {
			$status = _catalog_db_remove_section_recursive($child_section_id, $sections_info);
			if ($status === 'error') {
				return "error";
			}
		}
	}

	// теперь совершаем действия по удалению, собственно, секции

	// получаем список объектов в секции

	// удаляем файлы

	// удаляем записи в БД

	// удаляем привязки

	// удаляем запись в БД об секции
	$q = "delete from $catalog_sections_table where SECTION_ID = $section_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($section_id, 'catalog_section');
		if ($status === "error") {
			return "error";
		}

	}

	return "ok";

}

function catalog_db_get_section_description_info($section_id) {
	global $catalog_sections_table;
	global $ml_multilang_mode;

	$query = "select
				a.SECTION_ID, a.DESCRIPTION
			from
				$catalog_sections_table a
			where
				a.SECTION_ID = $section_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$return_data = $row;
	}

	if ($ml_multilang_mode) {
		unset($return_data['DESCRIPTION']);
		$section_data = db_ml_get_object_info($section_id, 'catalog_section');
		if ($section_data == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $section_data);
	}


	return $return_data;
}




function catalog_db_add_good($section_id, $title) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		//$title = addslashes($title);

		// запрос-добавление
		$q = "insert into 
				$catalog_goods_table
				(SECTION_ID, TITLE)
			values
				('$section_id', '')";
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}

		$good_id = balo3_db_last_insert_id();

		$status = db_ml_save_object_strings($good_id, 'catalog_good', 
			array(
				'TITLE' => $title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$title = addslashes($title);

		// запрос-добавление
		$q = "insert into 
				$catalog_goods_table
				(SECTION_ID, TITLE)
			values
				('$section_id', '$title')";
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}

		$good_id = balo3_db_last_insert_id();
	}

	return $good_id;
}

function catalog_db_modify_good($good_id, $good_info) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$set = array();
	if (isset($good_info['SECTION_ID'])) {
		$set[] = "SECTION_ID = '" . $good_info['SECTION_ID'] . "'";
	}
	if (isset($good_info['PUBLISHED'])) {
		$set[] = "PUBLISHED = '" . $good_info['PUBLISHED'] . "'";
	}
	if (isset($good_info['SOLD'])) {
		$set[] = "SOLD = '" . $good_info['SOLD'] . "'";
	}
	if (isset($good_info['TITLE']) && (!$ml_multilang_mode)) {
		$set[] = "TITLE = '" . addslashes($good_info['TITLE']) . "'";
	}
	if (isset($good_info['SHORT_TEXT']) && (!$ml_multilang_mode)) {
		$set[] = "SHORT_TEXT = '" . addslashes($good_info['SHORT_TEXT']) . "'";
	}
	if (isset($good_info['DESCRIPTION']) && (!$ml_multilang_mode)) {
		$set[] = "DESCRIPTION = '" . addslashes($good_info['DESCRIPTION']) . "'";
	}
	if (isset($good_info['SORT_VALUE']) ) {
		$set[] = "SORT_VALUE = '" . addslashes($good_info['SORT_VALUE']) . "'";
	}

	if (count($set) == 0) {
		return "ok";
	}

	$set_str = implode(", ", $set);

	$q = "update
			$catalog_goods_table
		set
			$set_str
		where
			GOOD_ID = $good_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if ($ml_multilang_mode) {

		if (isset($good_info['TITLE'])) {
			$status = db_ml_save_object_strings($good_id, 'catalog_good', 
				array(
					'TITLE' => $good_info['TITLE']
				));
			if ($status === "error") {
				return "error";
			}
		}

		$texts = array();
		if (isset($good_info['SHORT_TEXT'])) {
			$texts['SHORT_TEXT'] = $good_info['SHORT_TEXT'];
		}
		if (isset($good_info['DESCRIPTION'])) {
			$texts['DESCRIPTION'] = $good_info['DESCRIPTION'];
		}
		if (count($texts) > 0) {
			$status = db_ml_save_object_texts($good_id, 'catalog_good',
				$texts
				);
			if ($status === "error") {
				return "error";
			}
		}

	}

	return "ok";
}

function catalog_db_remove_good($good_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$q = "delete
		from
			$catalog_goods_table
		where
			GOOD_ID = $good_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}


	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($good_id, 'catalog_good');
		if ($status === "error") {
			return "error";
		}

	}


	return "ok";
}

function catalog_db_get_good_info($good_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	//
	$q = "select
			g.*
		from
			$catalog_goods_table g
		where
			GOOD_ID = $good_id";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if($row = mysql_fetch_assoc($res)) {
		$return_data = $row;
	} else {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		unset($return_data['TITLE']);
		unset($return_data['SHORT_TEXT']);
		unset($return_data['DESCRIPTION']);
		$good_data = db_ml_get_object_info($good_id, 'catalog_good');
		if ($good_data == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $good_data);
	}

	return $return_data;
}

function catalog_db_get_goods_counts($sections_ids = array(), $only_published = false) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	if (is_array($sections_ids)) {
		$sections_ids_str = implode(",", $sections_ids);
	} else {
		$sections_ids_str = $sections_ids;
	}

	$where = array();
	if ($sections_ids_str != '') {
		$where[] = "g.SECTION_ID in ($sections_ids_str)";
	}
	if ($only_published) {
		$where[] = "g.PUBLISHED = 1";
	}
	if (count($where) > 0) {
		$where_str = "where " . implode(" and ", $where);
	}

	$q = "select
				g.SECTION_ID, count(g.GOOD_ID) as count, sum(g.PUBLISHED) as published
			from
				$catalog_goods_table g
			$where_str
			group by
				g.SECTION_ID";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$counts = array();
	while($row = mysql_fetch_assoc($res)) {
		$counts[$row['SECTION_ID']] = $row;
	}

	return $counts;
}

function catalog_db_search_goods($s, $filters = array(), $sorting = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$good_ids = array();

	if ($ml_multilang_mode) {
		global $ml_current_language_id;
		global $ml_strings_table, $ml_texts_table;

		$s = addslashes($s);

		$q = "select
				ms.OBJECT_ID
			from
				$ml_strings_table ms
			where
				ms.LANGUAGE_ID = $ml_current_language_id
				and OBJECT_TYPE = 'catalog_good'
				and ms.FIELD = 'TITLE'
				and ms.STRING_VALUE like '%$s%'
			union
				select
				ms.OBJECT_ID
			from
				$ml_texts_table ms
			where
				ms.LANGUAGE_ID = $ml_current_language_id
				and OBJECT_TYPE = 'catalog_good'
				and (ms.FIELD = 'SHORT_TEXT' or ms.FIELD = 'DESCRIPTION')
				and ms.TEXT_VALUE like '%$s%'";
		//echo "<pre>$q</pre>";
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$good_ids[] = $row['OBJECT_ID'];
		}
		//show_ar($good_ids);

	} else {

		$s = addslashes($s);

		$q = "select
				GOOD_ID
			from
				$catalog_goods_table g
			where
				g.TITLE like '%$s%'
				or g.SHORT_TEXT like '%$s%'
				or g.DESCRIPTION like '%$s%'
			";
		//echo "<pre>$q</pre>";
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$good_ids[$row['GOOD_ID']] = $row['GOOD_ID'];
		}
	}

	if (count($good_ids) > 0) {
		$filters['good_ids'] = $good_ids;
	} else {
		return array();
	}

	return catalog_db_get_goods_list($filters, $sorting);
}

function catalog_db_get_goods_list($filters = array(), $sorting = array(), $limit = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table, $catalog_r_goods_sections_table;
	global $ml_multilang_mode, $ml_current_language_id;

	$join = array();

	// filters: section_id, published
	$where = array();
	if (isset($filters['strict_section_id'])) {
		$where[] = "g.SECTION_ID = " . $filters['strict_section_id'];
	}
	if (isset($filters['section_id'])) {
		$tmp_section_id = addslashes($filters['section_id']);
		$join[] = "left outer join $catalog_r_goods_sections_table rgs on (g.GOOD_ID = rgs.GOOD_ID and rgs.SECTION_ID = '$tmp_section_id')";
		$select[] = "case when isnull(rgs.SORT_VALUE) then g.SORT_VALUE else rgs.SORT_VALUE end as COMPLEX_SORT_VALUE";
		$where[] = " ( g.SECTION_ID = '$tmp_section_id' or rgs.SECTION_ID = '$tmp_section_id' ) ";
	}
	if (isset($filters['strict_section_ids']) && (count($filters['strict_section_ids']) > 0) ) {
		$where[] = "g.SECTION_ID in (" . implode(",", $filters['strict_section_ids']) . ")";
	}
	if (isset($filters['section_ids']) && (count($filters['section_ids']) > 0) ) {
		$join[] = "left outer join $catalog_r_goods_sections_table rgs on (g.GOOD_ID = rgs.GOOD_ID and rgs.SECTION_ID in (" . implode(",", $filters['section_ids']) . "))";
		$select[] = "case when isnull(rgs.SORT_VALUE) then g.SORT_VALUE else rgs.SORT_VALUE end as COMPLEX_SORT_VALUE";
		$where[] = "g.SECTION_ID in (" . implode(",", $filters['section_ids']) . ")";
	}
	if (isset($filters['published'])) {
		$where[] = "g.PUBLISHED = " . $filters['published'];
	}
	if (isset($filters['good_ids'])) {
		$where[] = "g.GOOD_ID in (" . implode(",", $filters['good_ids']) . ")";
	}
	if (isset($filters['search_str'])) {
		if ($ml_multilang_mode) {
			$join[] = "left outer join ML_STRINGS mls_t1 on (mls_t1.OBJECT_TYPE = 'catalog_good' and mls_t1.FIELD = 'TITLE' and mls_t1.OBJECT_ID = g.GOOD_ID)";
			$where[] = "mls_t1.STRING_VALUE like '%" . addslashes($filters['search_str']) . "%'";
		} else {
			$where[] = "g.TITLE like '%" . addslashes($filters['search_str']) . "%'";
		}
	}

	// sorting: array [field=>dir, ...]
	$order = array();
	foreach($sorting as $f=>$d) {
		if (in_array($d, array("asc", "desc"))) {
			if (in_array($f, array("title", "sort_value", "complex_sort_value", "good_id", "published", "sold"))) {
				if ($f == 'title' && $ml_multilang_mode) {
					$join[] = "left outer join ML_STRINGS mls_t on (mls_t.OBJECT_TYPE = 'catalog_good' and mls_t.FIELD = 'TITLE' and mls_t.OBJECT_ID = g.GOOD_ID)";
					$where[] = "mls_t.LANGUAGE_ID = '" . $ml_current_language_id . "' ";
					$f = "mls_t.STRING_VALUE";
				} elseif ($f == 'complex_sort_value') { 
					$f = "COMPLEX_SORT_VALUE";
				} else {
					$f = "g." . strtoupper($f);
				}
				$order[] = "$f $d";
			}
		} elseif ($f == "rand") {
			$order[] = "rand()";
		}
	}

	//show_ar($order);

	$select_str = "";
	if (count($select) > 0) {
		$select_str = ",\n" . implode(",", $select);
	}
	if (count($join) > 0) {
		$join_str = implode(" \n", $join);
	}
	if (count($where) > 0) {
		$where_str = "where " . implode(" and ", $where);
	}
	if (count($order) > 0) {
		$order_str = "order by " . implode(", ", $order);
	}
	if (isset($limit['limit'])) {
		$limit_limit = $limit['limit'];
		if (isset($limit['skip'])) {
			$limit_str = "limit $limit_skip, $limit_limit";
		} else {
			$limit_str = "limit $limit_limit";
		}
	}

	//
	$q = "select
			g.* $select_str
		from
			$catalog_goods_table g
			$join_str
		$where_str
		$order_str
		$limit_str";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$goods_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$goods_list[$row['GOOD_ID']] = $row;
	}
	
	//show_ar($goods_list);

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($goods_list) > 0) ) {

		foreach($goods_list as &$good) {
			unset($good['TITLE']);
			unset($good['SHORT_TEXT']);
			unset($good['DESCRIPTION']);
		}

		$good_datas = db_ml_get_objects_info(array_keys($goods_list), 'catalog_good');

		foreach($good_datas as $good_id => $good_data) {
			foreach($good_data as $f => $v) {
				$goods_list[$good_id][$f] = $v;
			}
		}

	}

	return $goods_list;
}


function catalog_db_add_price($good_id, $price_info) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$price = addslashes($price_info['PRICE']);
	$currency_id = addslashes($price_info['CURRENCY_ID']);
	$type = addslashes($price_info['TYPE']);

	$q = "insert into
		$catalog_prices_table
			(GOOD_ID, PRICE, CURRENCY_ID, TYPE)
		values
			($good_id, '$price', $currency_id, '$type')";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return balo3_db_last_insert_id();
}

function catalog_db_modify_price($price_id, $price_info) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$set = array();
	if (isset($price_info['PRICE'])) {
		$set[] = "PRICE = '" . addslashes($price_info['PRICE']) . "'";
	}
	if (isset($price_info['CURRENCY_ID'])) {
		$set[] = "CURRENCY_ID = '" . addslashes($price_info['CURRENCY_ID']) . "'";
	}
	if (isset($price_info['PRICE'])) {
		$set[] = "TYPE = '" . addslashes($price_info['TYPE']) . "'";
	}

	if (count($set) == 0) {
		return "ok";
	}

	$set_str = implode(", ", $set);

	$q = "update
			$catalog_prices_table
		set
			$set_str
		where
			PRICE_ID = $price_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function catalog_db_remove_prices($good_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$q = "delete
		from
			$catalog_prices_table
		where
			GOOD_ID = $good_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function catalog_db_remove_price($price_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$q = "delete
		from
			$catalog_prices_table
		where
			PRICE_ID = $price_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function catalog_db_get_all_prices($good_ids) {
	return catalog_db_get_prices($good_ids, false);
}

function catalog_db_get_prices($good_ids, $no_hidden_prices = true) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;

	if (is_array($good_ids)) {
		$good_ids_str = implode(",", $good_ids);
	} else {
		$good_ids_str = $good_ids;
	}
	if ($good_ids_str == '') {
		return array();
	}

	$whereadd = "";
	if ($no_hidden_prices) {
		$whereadd = "and p.TYPE != 'hidden'";
	}

	$q = "select
			p.*
		from
			$catalog_prices_table p
		where
			GOOD_ID in ($good_ids_str)
			$whereadd";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$prices_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$prices_list[$row['GOOD_ID']][$row['PRICE_ID']] = $row;
	}

	return $prices_list;

}

function catalog_db_get_goods_additional_sections($good_ids) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_r_goods_sections_table;


	if (is_array($good_ids)) {
		$good_ids_str = implode(",", $good_ids);
	} else {
		$good_ids_str = $good_ids;
	}
	if ($good_ids_str == '') {
		return array();
	}


	$q = "select
			*
		from
			$catalog_r_goods_sections_table
		where
			GOOD_ID in ($good_ids_str)";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$good_sections_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$good_sections_list[$row['GOOD_ID']][$row['SECTION_ID']] = $row;
	}

	return $good_sections_list;
}

function catalog_db_get_good_additional_sections($good_id) {

	$r = catalog_db_get_goods_additional_sections($good_id);
	if ($r === 'error') {
		return "error";
	}

	return $r[$good_id];
}

function catalog_db_set_good_additional_section($good_id, $section_id) {
	global $catalog_r_goods_sections_table;

	$q = "replace into 
			$catalog_r_goods_sections_table 
		(GOOD_ID, SECTION_ID)
			values
		('$good_id', '$section_id')";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function catalog_db_remove_good_additional_section($good_id, $section_id) {
	global $catalog_r_goods_sections_table;

	$q = "delete from $catalog_r_goods_sections_table where GOOD_ID = '$good_id' and SECTION_ID = '$section_id'";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function catalog_db_update_good_additional_section_info($good_id, $section_id, $sort_value) {
	global $catalog_r_goods_sections_table;

	$sort_value = addslashes($sort_value);

	$q = "update
			$catalog_r_goods_sections_table
			set
				SORT_VALUE = '$sort_value'
			where
				GOOD_ID = '$good_id'
				and SECTION_ID = '$section_id'";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}


function catalog_db_add_foto($good_id, $foto_info) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$sort_value = addslashes($foto_info['SORT_VALUE']);
	if ($sort_value == '') {
		$sort_value = 100;
	}
	$tech_info = addslashes(serialize($foto_info['TECH_INFO']));

	$q = "insert into
		$catalog_fotos_table
			(GOOD_ID, TECH_INFO, SORT_VALUE)
		values
			($good_id, '$tech_info', $sort_value)";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return balo3_db_last_insert_id();
}

function catalog_db_modify_foto($good_foto_id, $foto_info) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$set = array();
	if (isset($foto_info['SORT_VALUE'])) {
		$set[] = "SORT_VALUE = '" . addslashes($foto_info['SORT_VALUE']) . "'";
	}
	if (isset($foto_info['TECH_INFO'])) {
		$set[] = "TECH_INFO = '" . addslashes(serialize($foto_info['TECH_INFO'])) . "'";
	}

	if (count($set) == 0) {
		return "ok";
	}

	$set_str = implode(", ", $set);

	$q = "update
			$catalog_fotos_table
		set
			$set_str
		where
			GOOD_FOTO_ID = $good_foto_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function catalog_db_remove_foto($good_foto_id) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$q = "delete
		from
			$catalog_fotos_table
		where
			GOOD_FOTO_ID = $good_foto_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function catalog_db_get_all_fotos() {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;


	$q = "select
			f.*
		from
			$catalog_fotos_table f
		order by
			f.SORT_VALUE";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$fotos_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		$fotos_list[$row['GOOD_FOTO_ID']] = $row;
	}

	return $fotos_list;
}


function catalog_db_get_fotos($good_ids) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	if (is_array($good_ids)) {
		$good_ids_str = implode(",", $good_ids);
	} else {
		$good_ids_str = $good_ids;
	}
	if ($good_ids_str == '') {
		return array();
	}

	$q = "select
			f.*
		from
			$catalog_fotos_table f
		where
			GOOD_ID in ($good_ids_str)
		order by
			f.SORT_VALUE";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$fotos_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		$fotos_list[$row['GOOD_ID']][$row['GOOD_FOTO_ID']] = $row;
	}

	return $fotos_list;
}


function catalog_db_get_top_fotos($good_ids) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	if (is_array($good_ids)) {
		$good_ids_str = implode(",", $good_ids);
	} else {
		$good_ids_str = $good_ids;
	}
	if ($good_ids_str == '') {
		return array();
	}

	$q = "select
			f.*
		from
			$catalog_fotos_table f
		where
			GOOD_ID in ($good_ids_str)
		order by
			f.SORT_VALUE";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$fotos_list = array();
	while($row = mysql_fetch_assoc($res)) {
		// берём только первую фотку для каждого лота, остальные не нужны
		if (isset($fotos_list[$row['GOOD_ID']])) {
			continue;
		}
		$row['TECH_INFO'] = unserialize($row['TECH_INFO']);
		$fotos_list[$row['GOOD_ID']] = $row;
	}

	return $fotos_list;
}

function catalog_db_replace_specials_tag($good_ids) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;
	global $ml_multilang_mode;

	$q = "select TAG_ID from $catalog_tags_table where TAG = '_special'";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	if($row = mysql_fetch_assoc($r)) {
		$tag_id = $row['TAG_ID'];
	} else {
		return;
	}

	$q = "delete from $catalog_r_tags_goods_table where TAG_ID = $tag_id";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	$vals = array();
	foreach($good_ids as $good_id) {
		$vals[] = "($good_id, $tag_id)";
	}
	$q = "replace into $catalog_r_tags_goods_table (GOOD_ID, TAG_ID) values " . implode(",", $vals);
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	return;
}


function catalog_db_get_all_tags() {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;
	global $ml_multilang_mode;

	$q = "select
			*
		from
			$catalog_tags_table t";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	$tags_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$tags_list[$row['TAG_ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($tags_list) > 0) ) {

		foreach($tags_list as &$tag) {
			unset($tag['TAG']);
		}

		$tag_datas = db_ml_get_objects_info(array_keys($tags_list), 'catalog_tag');

		foreach($tag_datas as $tag_id => $tag_data) {
			foreach($tag_data as $f => $v) {
				$tags_list[$tag_id][$f] = $v;
			}
		}

	}

	return $tags_list;
}

function catalog_db_get_good_tags($good_id) {

	$tags = catalog_db_get_goods_tags($good_id);

	return $tags[$good_id];
}

function catalog_db_get_goods_tags($good_ids) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;

	if (is_array($good_ids)) {
		$good_ids_str = implode(",", $good_ids);
	} else {
		$good_ids_str = $good_ids;
	}
	if ($good_ids_str == '') {
		return array();
	}

	$q = "select
			rgt.GOOD_ID, rgt.TAG_ID, t.TAG
		from
			$catalog_r_tags_goods_table rgt
			left outer join $catalog_tags_table t using (TAG_ID)
		where
			rgt.GOOD_ID in ($good_ids_str)";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	$goods_tags = array();
	$tags_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$tags_list[] = $row;
		$goods_tags[$row['GOOD_ID']][$row['TAG_ID']] = $row['TAG'];
	}

	return $goods_tags;

}


function catalog_db_set_good_tags($good_id, $tags_list = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;

	// сначала удалим старые тэги товара
	$q = "delete
			from $catalog_r_tags_goods_table
			where
				GOOD_ID = $good_id";
	//echo "<pre>$q</pre>";
	$res = balo3_db_query($q);
	if (!$res) {
		return "error";
	}

	if (count($tags_list) == 0) {
		return "ok";
	}

	// достаем из базы id тех тэгов, которые уже есть
	$tags_list_str = '';
	foreach($tags_list as $tag) {
		$tags_list_str .= "'" . addslashes($tag) . "', ";
	}
	$tags_list_str = substr($tags_list_str, 0, -2);
	$q = "select
			t.TAG_ID, t.TAG
		from
			$catalog_tags_table t
		where
			TAG in ($tags_list_str)";
	//echo "<pre>$q</pre>";

	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}
	$tags_info = array();
	while($row = mysql_fetch_assoc($r)) {
		$tags_info[$row['TAG_ID']] = $row['TAG'];
	}

	// добавим тэги, которых нет в базе
	foreach($tags_list as $tag) {
		if (!in_array($tag, $tags_info)) {
			$q = "insert into $catalog_tags_table (TAG) values ('" . addslashes($tag) . "')";
			//echo "<pre>$q</pre>";
			$r = balo3_db_query($q);
			if (!$r) { return "error"; }
			$tag_id = balo3_db_last_insert_id();
			$tags_info[$tag_id] = $tag;
		}
	}

	// сохраним новые связи
	$r_tag_good = "";
	foreach(array_keys($tags_info) as $tag_id) {
		$r_tag_good .= "($tag_id, $good_id), ";
	}
	$r_tag_good = substr($r_tag_good, 0, -2);
	$q = "replace into $catalog_r_tags_goods_table (TAG_ID, GOOD_ID) values $r_tag_good";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) { return "error"; }


	catalog_db_clear_unused_tags();

	return "ok";

}

function catalog_db_clear_unused_tags() {
	global $catalog_tags_table, $catalog_r_tags_goods_table;

	$q = "select
				t.TAG_ID
			from
				$catalog_tags_table t
				left join $catalog_r_tags_goods_table rtg using (TAG_ID)
			group by
				t.TAG_ID
			having
				count(rtg.GOOD_ID) = 0";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	$tag_ids = array();
	while($row = mysql_fetch_assoc($r)) {
		$tag_ids[] = $row['TAG_ID'];
	}

	if (count($tag_ids) == 0) {
		return "ok";
	}

	$tag_ids_str = implode(",", $tag_ids);
	$q = "delete
			from
		$catalog_tags_table
			where
		TAG_ID in ($tag_ids_str)";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	return "ok";
}

function catalog_db_get_good_ids_by_tags($tags_list) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;

	if (count($tags_list) == 0) {
		return "ok";
	}

	$tags_list_str = '';
	foreach($tags_list as $tag) {
		$tags_list_str .= "'" . addslashes($tag) . "', ";
	}
	$tags_list_str = substr($tags_list_str, 0, -2);

	$q = "select
			rtg.TAG_ID, rtg.GOOD_ID
		from
			$catalog_tags_table t
			left outer join $catalog_r_tags_goods_table rtg using (TAG_ID)
		where
			t.TAG in ($tags_list_str)";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	$good_ids = array();
	while($row = mysql_fetch_assoc($r)) {
		$good_ids[] = $row['GOOD_ID'];
	}

	return $good_ids;
}

function catalog_db_filter_specials( $good_ids, $limit, $show_sold = false ) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $catalog_tags_table, $catalog_r_tags_goods_table;

	$limit = addslashes($limit);

	array_filter($good_ids, function($g) {
		return ctype_digit($g);
	});
	$good_ids_str = implode(", ", $good_ids);

	$where_add = "";
	if (!$show_sold) {
		$where_add = " and g.SOLD != 1 ";
	}

	$q = "select 
			g.GOOD_ID
		from
			CATALOG_GOODS g
		WHERE
			g.GOOD_ID in ($good_ids_str)
			$where_add
		order by
			rand()
		limit $limit";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		return "error";
	}

	$res_good_ids = array();
	while($row = mysql_fetch_assoc($r)) {
		$res_good_ids[] = $row['GOOD_ID'];
	}

	return $res_good_ids;
}

function catalog_db_new_search_goods($words = array(), $filters = array(), $sorting = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	$good_ids = array();

	if ($ml_multilang_mode) {

		$good_ids = multilang_db_search_objects($words, 'catalog_good', array('TITLE'), array('SHORT_TEXT', 'DESCRIPTION'));

	} else {

		// not supported yet
	}

	if (count($good_ids) > 0) {
		$filters['good_ids'] = $good_ids;
	} else {
		return array();
	}

	return catalog_db_get_goods_list($filters, $sorting);
}

function catalog_db_new_search_sections($words = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table;
	global $ml_multilang_mode;

	global $sections_info;

	$section_ids = array();

	if ($ml_multilang_mode) {

		$section_ids = multilang_db_search_objects($words, 'catalog_section', array('SECTION_NAME'), array('DESCRIPTION'));

	} else {

		// not supported yet
	}

	$sections_list = array();
	if (count($section_ids) > 0 ) {
		foreach($section_ids as $s_id){
			if ($sections_info['list'][$s_id]['PUBLISHED'] == 1) {
				$sections_list[$s_id] = $sections_info['list'][$s_id];
			}
		}
	}

	return $sections_list;
}

function catalog_db_new_search_tags($words = array()) {
	global $catalog_sections_table, $catalog_goods_table, $catalog_prices_table, $catalog_fotos_table, $catalog_tags_table;
	global $ml_multilang_mode;

	global $sections_info;

	$tags_list = array();

	if ($ml_multilang_mode) {

		foreach($words as $word_forms) {
			$orpart = array();
			foreach($word_forms as $form) {
				$orpart[] = "concat_ws(' ', t.TAG, t.TAG_TITLE, t.TAG_TEXT) like '%" . addslashes($form) . "%'";
			}
			if (count($orpart) > 0) {
				$andpart[] = "(".implode(" or ", $orpart).")";
			}
		}
		if (count($andpart) == 0) {
			return $tags_list;
		}
		$slike = implode(" and ", $andpart);

		$q = "select
				t.*
			from
				$catalog_tags_table t
			where
				$slike";
		//echo "<pre>$q</pre>";
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$tags_list[$row['TAG_ID']] = $row;
		}


	} else {

		// not supported yet
	}

	return $tags_list;
}

?>