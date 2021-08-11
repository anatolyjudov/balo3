<?php

/**
  * Get currently active popup with highest priority
  */
function popups_get_current() {
	global $popups_table_name;

	$query = "select
				*
			from
				$popups_table_name p
			where
				p.IS_HIDDEN = 0
			order by
				p.PRIORITY desc
			limit 1";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row;
	}

	return "notfound";
}

/**
  * Get all popups for admin's interface
  */
function popups_get_all() {
	global $popups_table_name;

	$query = "select
				*
			from
				$popups_table_name p
			order by
				p.IS_HIDDEN, p.PRIORITY desc";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$popups = array();
	while($row = mysql_fetch_assoc($res)) {
		$popups[$row['ID']] = $row;
	}

	return $popups;
}


function popups_set($id, $priority, $is_hidden) {
	global $popups_table_name;

	if (!ctype_digit((string)$id)) return "error";
	if (!ctype_digit((string)$priority)) return "error";
	if (!ctype_digit((string)$is_hidden)) return "error";

	$query = "update
				$popups_table_name
			set
				PRIORITY = '$priority',
				IS_HIDDEN = '$is_hidden'
			where
				ID = $id";

	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

?>