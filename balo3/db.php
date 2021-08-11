<?php

function balo3_db_sql_connect($dbhost, $dbuser, $dbpass, $dbname) {
	global $balo3_db;
	$balo3_db = mysql_connect($dbhost, $dbuser, $dbpass, true);
	if (!$balo3_db  || !mysql_select_db($dbname)) {
		die("mysql server connection error, ".mysql_errno().":".mysql_error());
		return false;
	} else {
		$query = "set names utf8";
		mysql_query($query, $balo3_db);
		return true;
	}
}

function balo3_db_query($query) {
	global $balo3_db;
	//echo "<pre>$query</pre>";
	$tmp = mysql_query($query, $balo3_db);
	return $tmp;
}

function balo3_db_last_insert_id() {
	global $balo3_db;

	return mysql_insert_id($balo3_db);
}

?>