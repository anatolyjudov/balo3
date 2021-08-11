<?php

// $words - двумерный массив вида "слово" => массив форм слова
function multilang_db_search_objects($words, $object_type, $string_fields_ar, $text_fields_ar) {
	global $ml_current_language_id;
	global $ml_strings_table, $ml_texts_table;

	if (!is_array($string_fields_ar)) {
		$string_fields = $string_fields_ar;
	} else {
		foreach($string_fields_ar as $f) {
			$string_fields[] = "'" . addslashes($f) . "'";
		}
		$string_fields = implode(",", $string_fields);
	}
	if (!is_array($text_fields_ar)) {
		$text_fields = $text_fields_ar;
	} else {
		foreach($text_fields_ar as $f) {
			$text_fields[] = "'" . addslashes($f) . "'";
		}
		$text_fields = implode(",", $text_fields);
	}

	$object_ids = array();

	$slike1 = "";
	$slike2 = "";
	$andpart1 = array();
	$andpart2 = array();
	foreach($words as $word_forms) {
		$orpart1 = array();
		$orpart2 = array();
		foreach($word_forms as $form) {
			$orpart1[] = "ms.STRING_VALUE like '%" . addslashes($form) . "%'";
			$orpart2[] = "mlt.TEXT_VALUE like '%" . addslashes($form) . "%'";
		}
		if(count($orpart1) > 0) {
			$andpart1[] = "(".implode(" or ", $orpart1).")";
			$andpart2[] = "(".implode(" or ", $orpart2).")";
		}
	}
	if(count($andpart1) > 0) {

		$slike1 = " and " . implode(" and ", $andpart1);
		$slike2 = " and " . implode(" and ", $andpart2);
		$q = "select
				ms.OBJECT_ID
			from
				$ml_strings_table ms
			where
				ms.LANGUAGE_ID = $ml_current_language_id
				and ms.OBJECT_TYPE = '$object_type'
				and ms.FIELD in ($string_fields)
				$slike1
			union
				select
					mlt.OBJECT_ID
			from
				$ml_texts_table mlt
			where
				mlt.LANGUAGE_ID = $ml_current_language_id
				and mlt.OBJECT_TYPE = '$object_type'
				and mlt.FIELD in ($text_fields)
				$slike2";
		//echo "<pre>$q</pre>";
		//exit;
		$res = balo3_db_query($q);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$object_ids[] = $row['OBJECT_ID'];
		}

	}

	return $object_ids;

}

/*
*
*/
function db_ml_remove_object_info($object_ids_arr, $object_type) {
	global $ml_strings_table, $ml_texts_table;

	if (!is_array($object_ids_arr)) {
		$object_ids = $object_ids_arr;
	} else {
		$object_ids = implode(",", $object_ids_arr);
	}
	$object_type = addslashes($object_type);

	$query = "delete from $ml_strings_table where OBJECT_ID in ($object_ids) and OBJECT_TYPE = '$object_type' ";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$query = "delete from $ml_texts_table where OBJECT_ID in ($object_ids) and OBJECT_TYPE = '$object_type' ";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}


/*
* object_data array ( field_name => field_value, ... )
*/
function db_ml_save_object_info($object_id, $object_type, $language_id, $object_data = array(), $data_type = 'strings') {
	global $ml_strings_table, $ml_texts_table;

	if ( ($data_type != 'strings') && ($data_type != 'texts') ) {
		return "error";
	}
	if ($data_type == 'strings') {
		$table_name = $ml_strings_table;
		$value_field = 'STRING_VALUE';
	}
	if ($data_type == 'texts') {
		$table_name = $ml_texts_table;
		$value_field = 'TEXT_VALUE';
	}

	if (count($object_data) == 0) {
		return "ok";
	}

	$object_id = addslashes($object_id);
	$object_type = addslashes($object_type);
	$language_id = addslashes($language_id);

	$records = array();
	foreach($object_data as $f=>$v) {
		$records[] = "('$language_id', '$object_id', '$object_type', '" . addslashes($f) . "', '" . addslashes($v) . "')";
	}
	if (count($records) == 0) {
		return "ok";
	}

	$query = "replace into
				$table_name
			(LANGUAGE_ID, OBJECT_ID, OBJECT_TYPE, FIELD, $value_field)
				values
			" . implode(", \n", $records) . "";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

/*
* object_data array ( field_name => field_value, ... )
*/
function db_ml_save_object_texts($object_id, $object_type, $object_data, $language_id = '') {
	global $ml_current_language_id;

	if ($language_id == '') {
		$language_id = $ml_current_language_id;
	}

	return db_ml_save_object_info($object_id, $object_type, $language_id, $object_data, 'texts');

}

/*
* object_data array ( field_name => field_value, ... )
*/
function db_ml_save_object_strings($object_id, $object_type, $object_data, $language_id = '') {
	global $ml_current_language_id;

	if ($language_id == '') {
		$language_id = $ml_current_language_id;
	}

	return db_ml_save_object_info($object_id, $object_type, $language_id, $object_data, 'strings');

}

/*
*
*/
function db_ml_get_object_info($object_id, $object_type, $language_id = '') {
	global $ml_current_language_id;

	if ($language_id == '') {
		$language_id = $ml_current_language_id;
	}

	$data = db_ml_get_objects_strings_and_texts(
					array(
					'language_ids' => array($language_id),
					'object_ids' => array($object_id),
					'object_types' => array($object_type),
					'get_strings' => TRUE,
					'get_texts' => TRUE
					));
	if ($data === 'error') {
		return "error";
	}

	$object_infos = array();
	foreach($data as $row) {
		$object_infos[$row['FIELD']] = $row['VALUE'];
	}

	return $object_infos;
}

/*
*
*/
function db_ml_get_object_info_certain($object_id, $object_type, $language_ids = array() ) {
	global $ml_current_language_id;

	if (!is_array($language_ids) || count($language_ids) == 0) {
		$language_ids = array($ml_current_language_id);
	}
	$data = db_ml_get_objects_strings_and_texts(
					array(
					'language_ids' => $language_ids,
					'object_ids' => array($object_id),
					'object_types' => array($object_type),
					'get_strings' => TRUE,
					'get_texts' => TRUE
					));
	if ($data === 'error') {
		return "error";
	}

	$object_infos = array();
	foreach($data as $row) {
		$object_infos[$row['FIELD']] = $row['VALUE'];
	}

	return $object_infos;
}

/*
*
*/
function db_ml_get_objects_info_certain( $object_ids, $object_type, $language_ids = array() ) {
	global $ml_current_language_id;

	if (!is_array($language_ids) || count($language_ids) == 0) {
		$language_ids = array($ml_current_language_id);
	}

	$data = db_ml_get_objects_strings_and_texts(
					array(
					'language_ids' => $language_ids,
					'object_ids' => $object_ids,
					'object_types' => array($object_type),
					'get_strings' => TRUE,
					'get_texts' => TRUE
					));
	if ($data === 'error') {
		return "error";
	}

	$objects_list = array();
	foreach($data as $row) {
		$objects_list[$row['OBJECT_ID']][$row['FIELD']] = $row['VALUE'];
	}

	return $objects_list;
}

/*
*
*/
function db_ml_get_objects_info($object_ids, $object_type, $language_id = '') {
	global $ml_current_language_id;

	if ($language_id == '') {
		$language_id = $ml_current_language_id;
	}

	$data = db_ml_get_objects_strings_and_texts(
					array(
					'language_ids' => array($language_id),
					'object_ids' => $object_ids,
					'object_types' => array($object_type),
					'get_strings' => TRUE,
					'get_texts' => TRUE
					));
	if ($data === 'error') {
		return "error";
	}

	$objects_list = array();
	foreach($data as $row) {
		$objects_list[$row['OBJECT_ID']][$row['FIELD']] = $row['VALUE'];
	}

	return $objects_list;
}

/*
*
*/
function db_ml_get_objects_list_info($object_ids, $object_type, $language_id = '') {
	global $ml_current_language_id;

	if ($language_id == '') {
		$language_id = $ml_current_language_id;
	}

	$data = db_ml_get_objects_strings_and_texts(
					array(
					'language_ids' => array($language_id),
					'object_ids' => $object_ids,
					'object_types' => array($object_type),
					'get_strings' => TRUE,
					'get_texts' => FALSE
					));
	if ($data === 'error') {
		return "error";
	}

	$objects_list = array();
	foreach($data as $row) {
		$objects_list[$row['OBJECT_ID']][$row['FIELD']] = $row['VALUE'];
	}

	return $objects_list;

}

/*
* filters array (language_ids => array(), object_ids => array(), object_types => array(), fields => array(), get_strings => boolean, get_texts => boolean )
*/
function db_ml_get_objects_strings_and_texts($filters) {
	global $ml_strings_table, $ml_texts_table;

	// принимаем фильтры
	if (isset($filters['language_ids']) && (count($filters['language_ids']) > 0) ) {
		$language_ids = $filters['language_ids'];
		foreach($language_ids as &$v) {
			$v = addslashes($v);
		}
	} else {
		$language_ids = array();
	}
	if (isset($filters['object_ids']) && (count($filters['object_ids']) > 0) ) {
		$object_ids = $filters['object_ids'];
		foreach($object_ids as &$v) {
			$v = addslashes($v);
		}
	} else {
		$object_ids = array();
	}
	if (isset($filters['object_types']) && (count($filters['object_types']) > 0) ) {
		$object_types = $filters['object_types'];
		foreach($object_types as &$v) {
			$v = addslashes($v);
		}
	} else {
		$object_types = array();
	}
	if (isset($filters['fields']) && (count($filters['fields']) > 0) ) {
		$fields = $filters['fields'];
		foreach($fields as &$v) {
			$v = addslashes($v);
		}
	} else {
		$fields = array();
	}

	if (isset($filters['get_strings']) && is_bool($filters['get_strings'])) {
		$get_strings = $filters['get_strings'];
	} else {
		$get_strings = FALSE;
	}
	if (isset($filters['get_texts']) && is_bool($filters['get_texts'])) {
		$get_texts = $filters['get_texts'];
	} else {
		$get_texts = FALSE;
	}

	// формируем запрос
	$whereclause_arr = array();
	$orderclause_arr = array();
	
	// id объектов
	if ( count($object_ids) > 0 ) {
		$whereclause_arr[] = "OBJECT_ID in (" . implode(",", $object_ids) . ")";
	}

	// типы объектов
	if ( count($object_types) > 0 ) {
		foreach( $object_types as $object_type ) {
			$object_types_list[] = "'$object_type'";
		}
		$whereclause_arr[] = "OBJECT_TYPE in (" . implode(",", $object_types_list) . ")";
	}

	// языки
	if ( count($language_ids) > 0 ) {
		$whereclause_arr[] = "LANGUAGE_ID in (" . implode(",", $language_ids) . ")";
		if ( count($language_ids) > 1 ) {
			$orderclause_arr[] = " find_in_set(LANGUAGE_ID, '" . implode(",", $language_ids) . "') ";
		}
	}

	// поля
	if ( count($fields) > 0 ) {
		foreach($fields as $field) {
			$fields_list[] = "'$field'";
		}
		$whereclause_arr[] = "FIELD in (" . implode(",", $fields_list) . ")";
	}

	// условие запроса
	if (count($whereclause_arr) > 0) {
		$whereclause = "where \n " . implode(" \n and ", $whereclause_arr);
	}

	$orderclause = "";
	if (count($orderclause_arr) > 0) {
		$orderclause = " order by \n " . implode(" , ", $orderclause_arr);
	}

	// запросы

	// массив для результатов
	$results_arr = array();

	// получаем строки
	if ($get_strings) {
		$query = "select
					*,
					STRING_VALUE as VALUE
				from
					$ml_strings_table
				$whereclause
				$orderclause";
		//echo "<pre>$query</pre>";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$results_arr[] = $row;
		}
	}

	// получаем тексты
	if ($get_texts) {
		$query = "select
					*,
					TEXT_VALUE as VALUE
				from
					$ml_texts_table
				$whereclause
				$orderclause";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
		while($row = mysql_fetch_assoc($res)) {
			$results_arr[] = $row;
		}
	}

	// выдаем все без разбора в виде массива
	return $results_arr;
}

?>