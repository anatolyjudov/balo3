<?

function meta_db_get_metainfo_by_id($meta_id) {
	global $meta_table;
	global $ml_multilang_mode;

	$query = "select * from $meta_table where META_ID = $meta_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$return_data = $row;
	} else {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		unset($return_data['TITLE']);
		unset($return_data['INNER_TITLE']);
		unset($return_data['KEYWORDS']);
		unset($return_data['DESCRIPTION']);
		unset($return_data['OG_META_TAGS']);
		$ml_info = db_ml_get_object_info($meta_id, 'meta');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;
}

function meta_db_add_metainfo($uri, $title, $inner_title, $keywords, $description, $error_state_local, $error_state_inet, $og_meta_tags, $head_image_foto_id = '', $meta_image_foto_id = '') {
	global $meta_table, $ml_multilang_mode;

	if ($head_image_foto_id === '') {
		$head_image_foto_id = 'NULL';
	} else {
		if (!preg_match("/^\d+$/", $head_image_foto_id)) {
			$head_image_foto_id = 'NULL';
		}
	}
	if ($meta_image_foto_id === '') {
		$meta_image_foto_id = 'NULL';
	} else {
		if (!preg_match("/^\d+$/", $meta_image_foto_id)) {
			$meta_image_foto_id = 'NULL';
		}
	}

	if ($ml_multilang_mode) {

		$uri = addslashes($uri);
		$error_state_local = addslashes($error_state_local);
		$error_state_inet = addslashes($error_state_inet);

		$query = "insert into $meta_table (URI, ERROR_STATE_LOCAL, ERROR_STATE_INET, HEAD_IMAGE_FOTO_ID, META_IMAGE_FOTO_ID)
				values ('$uri', '$error_state_local', '$error_state_inet', $head_image_foto_id, $meta_image_foto_id)";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$meta_id = mysql_insert_id();

		$status = db_ml_save_object_strings($meta_id, 'meta', 
			array(
				'TITLE' => $html_page_title,
				'INNER_TITLE' => $inner_title
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($meta_id, 'meta', 
			array(
				'KEYWORDS' => $keywords,
				'DESCRIPTION' => $description,
				'OG_META_TAGS' => $og_meta_tags
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$uri = addslashes($uri);
		$title = addslashes($title);
		$inner_title = addslashes($inner_title);
		$keywords = addslashes($keywords);
		$description = addslashes($description);
		$error_state_local = addslashes($error_state_local);
		$error_state_inet = addslashes($error_state_inet);
		$og_meta_tags = addslashes($og_meta_tags);

		$query = "insert into $meta_table (URI, TITLE, INNER_TITLE, KEYWORDS, DESCRIPTION, ERROR_STATE_LOCAL, ERROR_STATE_INET, OG_META_TAGS, HEAD_IMAGE_FOTO_ID, META_IMAGE_FOTO_ID)
				values ('$uri', '$title', '$inner_title', '$keywords', '$description', '$error_state_local', '$error_state_inet', '$og_meta_tags', $head_image_foto_id, $meta_image_foto_id)";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$meta_id = mysql_insert_id();
	}

	return $meta_id;
}

function meta_db_modify_metainfo($meta_id, $title, $inner_title, $keywords, $description, $error_state_local, $error_state_inet, $og_meta_tags, $head_image_foto_id = 'notchange', $meta_image_foto_id = 'notchange') {
	global $meta_table, $ml_multilang_mode;

	if ($head_image_foto_id === 'notchange') {
		$head_image_foto_id_update = "";
		$head_image_foto_id = 'NULL';
	} else {
		if (!preg_match("/^\d+$/", $head_image_foto_id)) {
			$head_image_foto_id = 'NULL';
		}
		$head_image_foto_id_update = ", HEAD_IMAGE_FOTO_ID = $head_image_foto_id";
	}

	if ($meta_image_foto_id === 'notchange') {
		$meta_image_foto_id_update = "";
		$meta_image_foto_id = 'NULL';
	} else {
		if (!preg_match("/^\d+$/", $meta_image_foto_id)) {
			$meta_image_foto_id = 'NULL';
		}
		$meta_image_foto_id_update = ", META_IMAGE_FOTO_ID = $meta_image_foto_id";
	}

	if ($ml_multilang_mode) {

		$error_state_local = addslashes($error_state_local);
		$error_state_inet = addslashes($error_state_inet);

		$query = "update
					$meta_table
				set
					ERROR_STATE_LOCAL = '$error_state_local',
					ERROR_STATE_INET = '$error_state_inet'
					$head_image_foto_id_update
					$meta_image_foto_id_update
				where
					META_ID = $meta_id";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$status = db_ml_save_object_strings($meta_id, 'meta', 
			array(
				'TITLE' => $title,
				'INNER_TITLE' => $inner_title
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($meta_id, 'meta', 
			array(
				'KEYWORDS' => $keywords,
				'DESCRIPTION' => $description,
				'OG_META_TAGS' => $og_meta_tags
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$title = addslashes($title);
		$inner_title = addslashes($inner_title);
		$keywords = addslashes($keywords);
		$description = addslashes($description);
		$error_state_local = addslashes($error_state_local);
		$error_state_inet = addslashes($error_state_inet);
		$og_meta_tags = addslashes($og_meta_tags);

		$query = "update
					$meta_table
				set
					TITLE = '$title',
					INNER_TITLE = '$inner_title',
					KEYWORDS = '$keywords',
					DESCRIPTION = '$description',
					ERROR_STATE_LOCAL = '$error_state_local',
					ERROR_STATE_INET = '$error_state_inet',
					OG_META_TAGS = '$og_meta_tags'
					$head_image_foto_id_update
					$meta_image_foto_id_update
				where
					META_ID = $meta_id";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

	}

	return "ok";
}

function meta_db_remove_metainfo($meta_id) {
	global $meta_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info(array($meta_id), 'meta');
		if ($status === "error") {
			return "error";
		}

	}

	$query = "delete from $meta_table where META_ID = $meta_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";

}


function meta_db_delete_metainfo($uri) {
	global $meta_table;
	global $ml_multilang_mode;

	$uri = addslashes("/" . trim($uri, " /") . "/");
	if ($uri == "//") {
		$uri = "/";
	}

	if ($ml_multilang_mode) {

		$query = "select META_ID from $meta_table where URI = '$uri'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
		
		$meta_ids = array();
		while($row = mysql_fetch_assoc($res)) {
			$meta_ids[] = $row['META_ID'];
		}

		if (count($meta_ids) > 0) {
			$status = db_ml_remove_object_info($meta_ids, 'meta');
			if ($status === "error") {
				return "error";
			}
		}

	}

	$query = "delete from $meta_table where URI = '$uri'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function meta_db_delete_metainfo_branch($uri) {
	global $meta_table;
	global $ml_multilang_mode;

	$uri = addslashes("/" . trim($uri, " /") . "/");
	if ($uri == "//") {
		$uri = "/";
	}

	if ($ml_multilang_mode) {

		$query = "select META_ID from $meta_table where URI like '$uri%'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
		
		$meta_ids = array();
		while($row = mysql_fetch_assoc($res)) {
			$meta_ids[] = $row['META_ID'];
		}

		if (count($meta_ids) > 0) {
			$status = db_ml_remove_object_info($meta_ids, 'meta');
			if ($status === "error") {
				return "error";
			}
		}

	}

	$query = "delete from $meta_table where URI like '$uri%'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function meta_db_move_metainfo($old_uri, $new_uri) {
	global $meta_table;

	$old_uri = addslashes("/" . trim($old_uri, " /") . "/");
	if ($old_uri == "//") {
		$old_uri = "/";
	}
	$old_uri_from = strlen($old_uri) + 1;

	$new_uri = addslashes("/" . trim($new_uri, " /") . "/");
	if ($new_uri == "//") {
		$new_uri = "/";
	}

	$query = "update
				$meta_table
			set
				URI = concat('$new_uri', substring(URI from $old_uri_from))
			where
				URI like '$old_uri%'";
	//echo $query;
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function meta_db_get_metainfo($uri) {
	global $meta_table;
	global $meta_title_concating, $meta_title_concating_string;
	global $ml_multilang_mode;

	if (trim($uri, "/") != "") {
		$uri_array = explode("/", trim($uri, "/"));
	} else {
		$uri_array = array();
	}

	// создаем список для where
	$whereclause = "where URI = '/' or ";
	$path = "/";
	foreach($uri_array as $folder_name) {
		$path .= $folder_name . "/";
		$whereclause .= "URI = '$path' or ";
	}
	$whereclause = substr($whereclause, 0, -4);

	// достаем все записи META для запрашиваемого URI и всех родительских
	$query = "select
				*
			from
				$meta_table
			$whereclause
			order by
				URI desc";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$metas_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$metas_list[$row['META_ID']] = $row;
	}

	if (count($metas_list) == 0) {
		return "notfound";
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($metas_list) > 0) ) {

		foreach($metas_list as &$meta_item) {
			unset($meta_item['TITLE']);
			unset($meta_item['INNER_TITLE']);
			unset($meta_item['KEYWORDS']);
			unset($meta_item['DESCRIPTION']);
			unset($meta_item['OG_META_TAGS']);
		}

		$meta_strings = db_ml_get_objects_info(array_keys($metas_list), 'meta');

		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$metas_list[$meta_id][$f] = $v;
			}
		}

	}

	// обрабатываем все эти поля
	$meta = array();
	$found = false;
	$error_state_local = array(403=>0, 404=>0);
	$error_state_inet = array(403=>0, 404=>0);
	$meta['ERROR_STATE_LOCAL'] = "none";
	$meta['ERROR_STATE_INET'] = "none";
	$meta['TITLE'] = "";
	$meta['KEYWORDS'] = "";
	$meta['DESCRIPTION'] = "";
	$meta['OG_META_TAGS'] = "";
	foreach($metas_list as $row) {
		$found = true;

		if (!isset($row['TITLE'])) $row['TITLE'] = '';
		if (!isset($row['KEYWORDS'])) $row['KEYWORDS'] = '';
		if (!isset($row['DESCRIPTION'])) $row['DESCRIPTION'] = '';
		if (!isset($row['OG_META_TAGS'])) $row['OG_META_TAGS'] = '';

		if ( $meta_title_concating && (trim($row['TITLE']) != "") ) {
			$meta['TITLE'] .= $row['TITLE'] . $meta_title_concating_string;
		} else {
			if ($meta['TITLE'] == "") {
				$meta['TITLE'] = $row['TITLE'];
			}
		}
		if (trim($row['URI'], "/") == trim($uri, "/")) {
			$meta['META_ID'] = $row['META_ID'];
		}
		if ($meta['KEYWORDS'] == "") {
			$meta['KEYWORDS'] = $row['KEYWORDS'];
		}
		if ($meta['DESCRIPTION'] == "") {
			$meta['DESCRIPTION'] = $row['DESCRIPTION'];
		}
		if ($meta['OG_META_TAGS'] == "") {
			$meta['OG_META_TAGS'] = $row['OG_META_TAGS'];
		}

		// МУТНАЯ ИСТОРИЯ С ОШИБКАМИ 403 и 404
		if ( ($row['ERROR_STATE_LOCAL'] === "403") || ($row['ERROR_STATE_LOCAL'] === "404") ) {
			if (trim($row['URI'], "/") == (trim ($uri, "/"))) {
				$row['ERROR_STATE_LOCAL'] = "+".$row['ERROR_STATE_LOCAL'];
			} else {
				$row['ERROR_STATE_LOCAL'] = "none";
			}
		}
		if ($row['ERROR_STATE_LOCAL'] != "none") {
			$code = substr($row['ERROR_STATE_LOCAL'], 1);
			$sign = substr($row['ERROR_STATE_LOCAL'], 0, -3);
			//echo $row['ERROR_STATE_LOCAL'].", $code, $sign<br>";
			if ($sign == "+") $error_state_local[$code] += 1;
			if ($sign == "-") $error_state_local[$code] -= 1;
		}

		if ( ($row['ERROR_STATE_INET'] === "403") || ($row['ERROR_STATE_INET'] === "404") ) {
			if (trim($row['URI'], "/") == (trim ($uri, "/"))) {
				$row['ERROR_STATE_INET'] = "+".$row['ERROR_STATE_INET'];
			} else {
				$row['ERROR_STATE_INET'] = "none";
			}
		}
		if ($row['ERROR_STATE_INET'] != "none") {
			$code = substr($row['ERROR_STATE_INET'], 1);
			$sign = substr($row['ERROR_STATE_INET'], 0, -3);
			if ($sign == "+") $error_state_inet[$code] += 1;
			if ($sign == "-") $error_state_inet[$code] -= 1;
		}
		//show_ar($error_state_inet);
		//show_ar($error_state_local);

		if (isset($row['HEAD_IMAGE_FOTO_ID']) && ($row['HEAD_IMAGE_FOTO_ID'] != '') && (!isset($meta['HEAD_IMAGE_FOTO_ID']))) {
			$meta['HEAD_IMAGE_FOTO_ID'] = $row['HEAD_IMAGE_FOTO_ID'];
		}
	}

	if ($found) {
		if ($error_state_inet['403'] > 0) {
			$meta['ERROR_STATE_INET'] = "403";
		}
		if ($error_state_inet['404'] > 0) {
			$meta['ERROR_STATE_INET'] = "404";
		}
		if ($error_state_local['403'] > 0) {
			$meta['ERROR_STATE_LOCAL'] = "403";
		}
		if ($error_state_local['404'] > 0) {
			$meta['ERROR_STATE_LOCAL'] = "404";
		}
		if ($meta_title_concating) {
			$meta['TITLE'] = substr($meta['TITLE'], 0, (-1)*strlen($meta_title_concating_string));
		}
		if (isset($meta['HEAD_IMAGE_FOTO_ID']) && ($meta['HEAD_IMAGE_FOTO_ID'] != '')) {
			$meta['HEAD_IMAGE_INFO'] = far_db_get_foto_info($meta['HEAD_IMAGE_FOTO_ID']);
			if ($meta['HEAD_IMAGE_INFO'] === "error") {
				return "error";
			}
			if ($meta['HEAD_IMAGE_INFO'] === "notfound") {
				$meta['HEAD_IMAGE_INFO'] = array();
			}
		}

		
		return $meta;
	}

	return "notfound";
}

function meta_db_get_metainfo_with_parents($uri) {
	global $meta_table;
	global $ml_multilang_mode;

	if (trim($uri, "/") != "") {
		$uri_array = explode("/", trim($uri, "/"));
	} else {
		$uri_array = array();
	}

	// создаем список для where
	$whereclause = "where URI = '/' or ";
	$path = "/";
	foreach($uri_array as $folder_name) {
		$path .= $folder_name . "/";
		$whereclause .= "URI = '$path' or ";
	}
	$whereclause = substr($whereclause, 0, -4);

	$query = "select
				*
			from
				$meta_table
			$whereclause
			order by
				URI";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$meta = array();
	while($row = mysql_fetch_assoc($res)) {
		$meta[$row['META_ID']] = $row;
	}

	//show_ar($meta);

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($meta) > 0) ) {

		foreach($meta as &$meta_item) {
			unset($meta_item['TITLE']);
			unset($meta_item['INNER_TITLE']);
			unset($meta_item['KEYWORDS']);
			unset($meta_item['DESCRIPTION']);
			unset($meta_item['OG_META_TAGS']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($meta), 'meta');

		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$meta[$meta_id][$f] = $v;
			}
		}

	}

	return $meta;
}

function meta_db_get_meta_id($uri) {
	global $meta_table;

	$uri = "/" . trim($uri, "/") . "/";
	if ($uri == "//") $uri = "/";
	$uri = addslashes($uri);

	$query = "select META_ID from $meta_table where URI = '$uri'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row['META_ID'];
	}
	
	return "notfound";

}


function meta_db_set_metainfo($uri, $title, $inner_title, $keywords, $description, $error_state_local = 'none', $error_state_inet = 'none', $head_image_foto_id = 'notchange') {
	global $meta_table;
	global $ml_multilang_mode;

	$uri = addslashes("/" . trim($uri, " /") . "/");
	if ($uri == "//") {
		$uri = "/";
	}
	if (!$ml_multilang_mode) {
		$title = addslashes($title);
		$inner_title = addslashes($inner_title);
		$keywords = addslashes($keywords);
		$description = addslashes($description);
	}
	if ($head_image_foto_id === 'notchange') {
		$head_image_foto_id_update = "";
		$head_image_foto_id = 'NULL';
	} else {
		if (!preg_match("/^\d+$/", $head_image_foto_id)) {
			$head_image_foto_id = 'NULL';
		}
		$head_image_foto_id_update = "HEAD_IMAGE_FOTO_ID = $head_image_foto_id";
	}

	// проверим, есть ли такая запись в метаинфо
	$query = "select META_ID from $meta_table where URI = '$uri'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {

		$meta_id = $row['META_ID'];

		if ($head_image_foto_id_update != "") {
			$query = "update 
							$meta_table 
						set 
							$head_image_foto_id_update
						where
							META_ID = $meta_id";
			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
			if (!$res) {
				return "error";
			}
		}

		// такая запись есть, делаем апдейт
		if ($ml_multilang_mode) {

			$status = db_ml_save_object_strings($meta_id, 'meta', 
				array(
					'TITLE' => $title,
					'INNER_TITLE' => $inner_title
				));
			if ($status === "error") {
				return "error";
			}

			$status = db_ml_save_object_texts($meta_id, 'meta', 
				array(
					'KEYWORDS' => $keywords,
					'DESCRIPTION' => $description
				));
			if ($status === "error") {
				return "error";
			}


		} else {

			$query = "update 
							$meta_table 
						set 
							TITLE = '$title', INNER_TITLE = '$inner_title', KEYWORDS = '$keywords', DESCRIPTION = '$description'
						where
							META_ID = $meta_id";
			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
			if (!$res) {
				return "error";
			}

		}

	} else {

		// такой записи нет, делаем инсерт

		if ($ml_multilang_mode) {

			
			$query = "insert into $meta_table (URI, HEAD_IMAGE_FOTO_ID) values ('$uri', $head_image_foto_id)";
			$res = balo3_db_query($query);
			if (!$res) {
				return "error";
			}

			$meta_id = mysql_insert_id();

			$status = db_ml_save_object_strings($meta_id, 'meta', 
				array(
					'TITLE' => $title,
					'INNER_TITLE' => $inner_title
				));
			if ($status === "error") {
				return "error";
			}

			$status = db_ml_save_object_texts($meta_id, 'meta', 
				array(
					'KEYWORDS' => $keywords,
					'DESCRIPTION' => $description
				));
			if ($status === "error") {
				return "error";
			}

		} else {

			$query = "insert into $meta_table (URI, TITLE, INNER_TITLE, KEYWORDS, DESCRIPTION, HEAD_IMAGE_FOTO_ID) values ('$uri', '$title', '$inner_title', '$keywords', '$description', '$head_image_foto_id')";
			$res = balo3_db_query($query);
			if (!$res) {
				return "error";
			}

		}
	}

	return "ok";
}




function meta_db_get_metainfo_branch($uri_filter) {
	global $meta_table;
	global $manuka_table;
	global $ml_multilang_mode;

	$uri_filter= addslashes("/" . trim($uri_filter, " /") . "/");
	if ($uri_filter == "//") {
		$whereclause = "";
	} else {
		$whereclause = "where URI like '%$uri_filter%'";
	}

	$query = "select
				*
			from
				$meta_table
			$whereclause
			order by
				URI";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$meta = array();
	while($row = mysql_fetch_assoc($res)) {
		$meta[$row['META_ID']] = $row;
	}


	// в режиме мультиязычности достаем все мультиязычные поля
	if ( ($ml_multilang_mode) && (count($meta) > 0) ) {

		foreach($meta as &$meta_item) {
			unset($meta_item['TITLE']);
			unset($meta_item['INNER_TITLE']);
			unset($meta_item['KEYWORDS']);
			unset($meta_item['DESCRIPTION']);
			unset($meta_item['OG_META_TAGS']);
		}

		$meta_strings = db_ml_get_objects_info(array_keys($meta), 'meta');
		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$meta[$meta_id][$f] = $v;
			}
		}

	}

	return $meta;
}

function meta_db_get_metainfo_branch_uri_index($uri_begin) {
	global $meta_table;
	global $ml_multilang_mode;

	$uri_begin= addslashes("/" . trim($uri_begin, " /") . "/");
	if ($uri_begin == "//") {
		$whereclause = "";
	} else {
		$whereclause = "where URI like '$uri_begin%'";
	}

	$query = "select
				*
			from
				$meta_table
			$whereclause
			order by
				URI";
//	echo $query;
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$meta = array();
	while($row = mysql_fetch_assoc($res)) {
		$meta[$row['URI']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля
	if ( ($ml_multilang_mode) && (count($meta) > 0) ) {

		foreach($meta as &$meta_item) {
			unset($meta_item['TITLE']);
			unset($meta_item['INNER_TITLE']);
			unset($meta_item['KEYWORDS']);
			unset($meta_item['DESCRIPTION']);
			unset($meta_item['OG_META_TAGS']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($meta), 'meta');

		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$meta[$meta_id][$f] = $v;
			}
		}

	}

	return $meta;
}

function meta_db_get_metainfo_uri_childs($uri) {
	global $meta_table, $manuka_table, $htmls_table;
	global $ml_multilang_mode;

	$uri = addslashes("/" . trim($uri, " /") . "/");
	if ($uri == "//") {
		$uri = "/";
	}
	$whereclause = "where URI rlike '^".$uri."[^/]+/$'";

	$query = "select
				m.*,
				man.M_ID,
				ifnull(m.META_IMAGE_FOTO_ID, h.HTML_IMAGE_FOTO_ID) as META_IMAGE_FOTO_ID
			from
				$meta_table m
				left outer join $manuka_table man on (m.URI = man.M_PATH and man.M_COMPONENT = 'html' and man.M_EXECUTIVE = 'html.php')
				left outer join $htmls_table h on (man.M_INSTANCE = h.HTML_ID)
			$whereclause
			order by
				URI";
	//echo $query;
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$meta = array();
	while($row = mysql_fetch_assoc($res)) {
		$meta[$row['META_ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля
	if ( ($ml_multilang_mode) && (count($meta) > 0) ) {

		foreach($meta as &$meta_item) {
			unset($meta_item['TITLE']);
			unset($meta_item['INNER_TITLE']);
			unset($meta_item['KEYWORDS']);
			unset($meta_item['DESCRIPTION']);
			unset($meta_item['OG_META_TAGS']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($meta), 'meta');

		foreach($meta_strings as $meta_id => $object_data) {
			foreach($object_data as $f => $v) {
				$meta[$meta_id][$f] = $v;
			}
		}

	}

	return $meta;
}




?>