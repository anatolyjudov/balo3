<?php

//function staticpages_db_get_page_by_id($m_id) {
function staticpages_db_get_page_by_id($page_id) {
	global $staticpages_pages_table;
	global $ml_multilang_mode;

	$query = "select * from $staticpages_pages_table where HTML_ID = $page_id";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$return_data = $row;
	} else {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		unset($return_data['HTML_CONTENT']);
		unset($return_data['HTML_PAGE_TITLE']);
		$ml_info = db_ml_get_object_info($page_id, 'pages');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;

}

function staticpages_db_add_page($new_node_path, $controller_family, $controller, $layout, $placeholder) {
	global $staticpages_pages_table;
	global $manuka_table;

	// добавим новый экземпляр текстовой страницы, чтобы получить номер
	$html_id = staticpages_db_add_blank_page();
	if ($html_id == "error") {
		return "error";
	}

	$layout = addslashes($layout);
	$placeholder = addslashes($placeholder);
	$controller_args = $html_id;

	$node_id = manuka_db_add_node($new_node_path, 'strict', $controller_family, $controller, $layout, $placeholder, $controller_args);
	if ("error" == $node_id) {
		return "error";
	}

	return $node_id;
}

function staticpages_db_add_blank_page() {
	global $staticpages_pages_table;

	$query = "insert into $staticpages_pages_table (HTML_CONTENT, HTML_PAGE_TITLE) values ('', '')";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return mysql_insert_id();
}

function staticpages_db_update_page($page_id, $new_html_info, $html_image_foto_id = '') {
	global $staticpages_pages_table;
	global $ml_multilang_mode;

	if ($html_image_foto_id != '') {
		$image_foto_id_val = "'$html_image_foto_id'";
	} else {
		$image_foto_id_val = "NULL";
	}

	$plain_html_edit = addslashes($new_html_info['plain_html_edit']);

	if ($ml_multilang_mode) {

		$query = "update 
					$staticpages_pages_table
				set
					HTML_IMAGE_FOTO_ID = $image_foto_id_val,
					PLAIN_HTML_EDIT = $plain_html_edit
				where
					HTML_ID = $page_id";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$status = db_ml_save_object_strings($page_id, 'pages', array('HTML_PAGE_TITLE' => $new_html_info['html_page_title']));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($page_id, 'pages', array('HTML_CONTENT' => $new_html_info['html_content']));
		if ($status === "error") {
			return "error";
		}

	} else {

		$html_page_title = addslashes($new_html_info['html_page_title']);
		$html_content = addslashes($new_html_info['html_content']);

		/*$query = "replace into $staticpages_pages_table (HTML_ID, HTML_PAGE_TITLE, HTML_CONTENT, HTML_IMAGE_FOTO_ID) 
					values ($page_id, '$html_page_title', '$html_content', $image_foto_id_val)";*/
		$query = "update
					$staticpages_pages_table
				set
					HTML_PAGE_TITLE = '$html_page_title',
					HTML_CONTENT = '$html_content',
					HTML_IMAGE_FOTO_ID =  $image_foto_id_val,
					PLAIN_HTML_EDIT = $plain_html_edit
				where
					HTML_ID = $page_id";
		$res = balo3_db_query($query);
		if (!$res) {
			// обработка ошибки
			return "error";
		}

	}

	return "ok";
}

function staticpages_db_remove_page($node_id, $page_id) {
	global $staticpages_pages_table;
	global $manuka_table;
	global $ml_multilang_mode;

	// информация о вершине
	$manuka_node = manuka_db_get_node_info_by_id($node_id);
	if ($manuka_node == "notfound") {
		return "notfound";
	}
	if ($manuka_node == "error") {
		return "error";
	}

	$node_has_another_controllers = false;
	$page_id_found = false;
	$page_id_placeholder = '';
	foreach($manuka_node['controllers'] as $placeholder=>$controller_info) {
		if ( ($controller_info['CONTROLLER_FAMILY'] != "staticpages") || ($controller_info['CONTROLLER']!="page") ) {
			$node_has_another_controllers = true;
			continue;
		}
		if ($controller_info['CONTROLLER_ARGS'] == $page_id) {
			$page_id_found = true;
			$page_id_placeholder = $placeholder;
		}
	}

	if (!$page_id_found) {
		return "notfound";
	}

	// удаляем страницы
	$query = "delete from $staticpages_pages_table where HTML_ID in ($page_id)";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	// удаляем привязку контроллера к вершине
	$status = manuka_db_delete_node_controller_relation($node_id, $page_id_placeholder);
	if ("error" === $status) {
		return "error";
	}

	// удаляем вершину
	if (!$node_has_another_controllers) {
		$status = manuka_db_delete_node($node_id);
		if ("error" === $status) {
			return "error";
		}
	}

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info(array($page_id), 'pages');
		if ($status === "error") {
			return "error";
		}

	}

	return "ok";
}

function staticpages_db_get_tree($from_path, $show_all = true, $show_only_htmls = true, $path_filter = "") {
	global $html_exclude_branches;
	global $staticpages_pages_table;
	global $ml_multilang_mode;

	global $manuka_nodes_table, $manuka_r_nodes_controllers_table, $manuka_controllers_table;
	global $meta_table;

	$whereclause = "";			// будем собирать в этой переменной условие поиска

	if ($from_path!="") {
		$whereclause = " n.NODE_PATH like '$from_path%' and ";
	}

	$path_filter = addslashes($path_filter);
	if ($path_filter != "") {
		$whereclause .= " n.NODE_PATH like '%$path_filter%' and ";
	}

	if ((!$show_all) && (count($html_exclude_branches) > 0)) {
		foreach ($html_exclude_branches as $branch) {
			$whereclause .= " n.NODE_PATH not like '$branch%' and ";
		}
	}
	if ($show_only_htmls) {
		$whereclause .= " (c.CONTROLLER_FAMILY = 'staticpages') and (c.CONTROLLER = 'page') and ";
	}

	if ($whereclause != "") {
		$whereclause = "where " . substr($whereclause, 0, -4);
	}

	// делаем запрос, который достанет все вершины
	$query = "select 
				n.*, c.*, rnc.*,
				me.TITLE, me.INNER_TITLE, me.META_ID
			from
				$manuka_nodes_table n
				left outer join $manuka_r_nodes_controllers_table rnc using (NODE_ID)
				inner join $manuka_controllers_table c using (CONTROLLER_ID)
				left outer join $meta_table me on n.NODE_PATH = me.URI
			$whereclause 
			order by 
				n.NODE_PATH";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	$manuka_nodes = array();		// сюда будем все складывать
	while ($row = mysql_fetch_assoc($res)) {
		$manuka_nodes[$row['NODE_ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для сущности мета, которая связана с полями мануки
	if ( ($ml_multilang_mode) && (count($manuka_nodes) > 0) ) {

		$meta_ids = array();
		foreach($manuka_nodes as &$node) {
			unset($node['TITLE']);
			unset($node['INNER_TITLE']);
			if ((!isset($node['META_ID'])) || ($node['META_ID'] == '')) {
				continue;
			}
			$meta_ids[$node['META_ID']] = $node['NODE_ID'];
		}

		if (count($meta_ids) > 0) {

			$meta_strings = db_ml_get_objects_list_info(array_keys($meta_ids), 'meta');

			foreach($meta_strings as $meta_id => $object_data) {
				foreach($object_data as $f => $v) {
					$manuka_nodes[$meta_ids[$meta_id]][$f] = $v;
				}
			}

		}

	}


	return $manuka_nodes;

}

function staticpages_db_get_tree_list($from_node, $show_all = true, $levels_deep = 1) {
	global $html_exclude_branches;
	global $staticpages_pages_table;
	global $manuka_table, $meta_table;
	global $ml_multilang_mode;

	$show_only_htmls = true;

	$whereclause = "";			// будем собирать в этой переменной условие поиска

	// смотрим в дереве на ту вершину, которая пришла в параметре
	$query = "select M_PATH, M_TREE_LEVEL from $manuka_table where M_ID = $from_node";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	// найдено?
	if ($row = mysql_fetch_assoc($res)) {
		// изменяем условие поиска
		$from_path = $row['M_PATH'];
		$from_tree_level = $row['M_TREE_LEVEL'];
		$whereclause = " M_PATH like '$from_path%' and M_TREE_LEVEL <= " . ($from_tree_level + $levels_deep) . " and ";
	} else {
		// обработка ошибки (не найдено)
		return "notfound";
	}

	if ((!$show_all) && (count($html_exclude_branches) > 0)) {
		foreach ($html_exclude_branches as $branch) {
			$whereclause .= " M_PATH not like '$branch%' and ";
		}
	}
	if ($show_only_htmls) {
		$whereclause .= " (M_COMPONENT = 'html') and (M_EXECUTIVE = 'html.php') and ";
	}

	if ($whereclause != "") {
		$whereclause = "where " . substr($whereclause, 0, -4);
	}

	// делаем запрос, который достанет все вершины
	$query = "select 
				m.*, me.TITLE, me.INNER_TITLE, me.META_ID,
				h.HTML_ID, h.HTML_PAGE_TITLE, h.HTML_IMAGE_FOTO_ID
			from
				$manuka_table m
				left outer join $meta_table me on m.M_PATH = me.URI
				inner join $staticpages_pages_table h on m.M_INSTANCE = h.HTML_ID
			$whereclause 
			order by 
				M_PATH";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	$manuka_nodes = array();		// сюда будем все складывать
	while ($row = mysql_fetch_assoc($res)) {
		$manuka_nodes[$row['M_ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для сущности мета, которая связана с полями мануки
	if ( ($ml_multilang_mode) && (count($manuka_nodes) > 0) ) {

		// достанем мультиязычные поля для меты
		$meta_ids = array();
		foreach($manuka_nodes as &$node) {
			unset($node['TITLE']);
			unset($node['INNER_TITLE']);
			if ((!isset($node['META_ID'])) || ($node['META_ID'] == '')) {
				continue;
			}
			$meta_ids[$node['META_ID']] = $node['M_ID'];
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($meta_ids), 'meta');

		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$manuka_nodes[$meta_ids[$meta_id]][$f] = $v;
			}
		}

		// достанем мультиязычные поля для html
		$html_ids = array();
		foreach($manuka_nodes as &$node) {
			unset($node['HTML_PAGE_TITLE']);
			if ((!isset($node['HTML_ID'])) || ($node['HTML_ID'] == '')) {
				continue;
			}
			$html_ids[$node['HTML_ID']] = $node['M_ID'];
		}

		$html_strings = db_ml_get_objects_list_info(array_keys($html_ids), 'pages');

		foreach($html_strings as $html_id => $object_data) {
			foreach($object_data as $f => $v) {
				$manuka_nodes[$html_ids[$html_id]][$f] = $v;
			}
		}

	}


	return $manuka_nodes;

}

?>