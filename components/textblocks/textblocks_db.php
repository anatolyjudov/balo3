<?php

function list_textblocks() {
	global $textblocks_table;
	global $ml_multilang_mode;

	$query = "select * from $textblocks_table order by PATH asc";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	$textblocks = array();
	while ($row = mysql_fetch_array($result)) {
		$textblocks[$row['ID']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($textblocks) > 0) ) {

		foreach($textblocks as &$textblock) {
			unset($textblock['TEXT']);
		}

		$meta_strings = db_ml_get_objects_list_info(array_keys($textblocks), 'textblock');

		foreach($meta_strings as $textblock_id => $object_data) {
			foreach($object_data as $f => $v) {
				$textblocks[$textblock_id][$f] = $v;
			}
		}

	}
	return $textblocks;
}

function add_textblock ($name, $title, $text, $comment, $path, $bgcolor, $plain_html_edit) {
	global $textblocks_table;
	global $ml_multilang_mode;

	$name = addslashes($name);
	$comment = addslashes($comment);
	$path = addslashes($path);
	$bgcolor = addslashes($bgcolor);
	$plain_html_edit = addslashes($plain_html_edit);

	if ($ml_multilang_mode) {

		$query = "insert into $textblocks_table
					(NAME, COMMENT, PATH, BGCOLOR, PLAIN_HTML_EDIT)
				values ('$name', '$comment', '$path', '$bgcolor', '$plain_html_edit')";
		$result = balo3_db_query ($query);
		if (!$result) {
			return "error";
		}

		$textblock_id = mysql_insert_id();

		$status = db_ml_save_object_texts($textblock_id, 'textblock', 
			array(
				'TEXT' => $text
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_strings($textblock_id, 'textblock', 
			array(
				'TITLE' => $title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$text = addslashes($text);
		$title = addslashes($title);

		$query = "insert into $textblocks_table
					(NAME, TITLE, TEXT, COMMENT, PATH, BGCOLOR, PLAIN_HTML_EDIT)
				values ('$name', '$title', '$text', '$comment', '$path', '$bgcolor', '$plain_html_edit')";
		$result = balo3_db_query ($query);
		if (!$result) {
			return "error";
		}

	}

}

function info_textblock($textblock_id) {
	global $textblocks_table;
	global $ml_multilang_mode;

	$query = "select * from $textblocks_table where ID='$textblock_id'";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	$return_data = mysql_fetch_array($result);

	if ($ml_multilang_mode) {
		unset($return_data['TITLE']);
		unset($return_data['TEXT']);
		$ml_info = db_ml_get_object_info($textblock_id, 'textblock');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;
}

function modify_textblock ($id, $title, $name, $text, $comment, $path, $bgcolor, $plain_html_edit) {
	global $textblocks_table;
	global $ml_multilang_mode;

	$name = addslashes($name);
	$comment = addslashes($comment);
	$path = addslashes($path);
	$bgcolor = addslashes($bgcolor);
	$plain_html_edit = addslashes($plain_html_edit);

	if ($ml_multilang_mode) {

		$query = "update
					$textblocks_table
				set
					NAME='$name',
					COMMENT='$comment',
					PATH='$path',
					BGCOLOR = '$bgcolor',
					PLAIN_HTML_EDIT = '$plain_html_edit'
				where
					ID='$id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

		$status = db_ml_save_object_texts($id, 'textblock', 
			array(
				'TEXT' => $text
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_strings($id, 'textblock', 
			array(
				'TITLE' => $title
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$text = addslashes($text);
		$title = addslashes($title);
		$query = "update
					$textblocks_table
				set
					NAME='$name',
					TITLE='$title',
					TEXT='$text',
					COMMENT='$comment',
					PATH='$path',
					BGCOLOR = '$bgcolor',
					PLAIN_HTML_EDIT = '$plain_html_edit'
				where
					ID='$id'";
		$result = balo3_db_query($query);
		if (!$result) {
			return "error";
		}

	}

	return "ok";
}

function remove_textblock($id) {
	global $textblocks_table;
	global $ml_multilang_mode;

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($id, 'textblock');
		if ($status === "error") {
			return "error";
		}

	}

	$query = "delete from $textblocks_table where ID='$id'";
	$result = balo3_db_query ($query);
	if (!$result) {
		return "error";
	}
}


function module_show_textblock($textblock_identify) {
	global $textblocks_table;
	global $ml_multilang_mode;

	if (preg_match("/^\d+$/", $textblock_identify)) {
		$whereclause = "ID='$textblock_identify'";
	} else {
		$whereclause = "NAME='" . addslashes($textblock_identify) . "'";
	}
	
	$query = "select 
				*
			from
				$textblocks_table
			where
				$whereclause";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	$return_data = mysql_fetch_array($result);
	if (mysql_num_rows($result)==0) {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		$textblock_id = $return_data['ID'];
		unset($return_data['TEXT']);
		$ml_info = db_ml_get_object_info($textblock_id, 'textblock');
		if ($ml_info == 'error') {
			return "error";
		}
		$return_data = array_merge($return_data, $ml_info);
	}

	return $return_data;
}

?>