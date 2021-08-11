<?php

function search_db_htmls($search, $results_on_page, $skip){
	global $domain;
	global $root_path;
	global $search_areas;
	global $components_path;

	require("$components_path/staticpages/staticpages_config.php");
	require("$components_path/manuka/manuka_config.php");

	if (($results_on_page=="") and ($skip=="")){
		$limit_clause = "";
	}else{
		$limit_clause = "limit $skip, $results_on_page";
	}
	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.NODE_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
			h.HTML_CONTENT as content, 
			h.HTML_PAGE_TITLE as title, 
			concat('$domain', '$root_path', m.NODE_PATH) as path,
			m.NODE_PATH as path_access
		from 
			$manuka_controllers_table mc
			left outer join $manuka_r_nodes_controllers_table rnc using (CONTROLLER_ID)
			inner join $manuka_nodes_table m using (NODE_ID)
			left outer join $staticpages_pages_table h on h.HTML_ID = rnc.CONTROLLER_ARGS
		where
			(mc.CONTROLLER_FAMILY = 'staticpages' and mc.CONTROLLER = 'page')
			and
			((h.HTML_PAGE_TITLE like '%$search%') or (h.HTML_CONTENT like '%$search%'))
			$where_additional
		$limit_clause";
	echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	while ($row = mysql_fetch_assoc($result)) {
		$found[] = $row;
	}
	return $found;
}

function search_db_count_htmls ($search){
	global $search_areas;
	global $domain;
	global $root_path;
	global $components_path;

	require("$components_path/staticpages/staticpages_config.php");
	require("$components_path/manuka/manuka_config.php");

	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.M_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
				count(h.HTML_ID) as c
			from 
				$manuka_controllers_table mc
				left outer join $manuka_r_nodes_controllers_table rnc using (CONTROLLER_ID)
				inner join $manuka_nodes_table m using (NODE_ID)
				left outer join $staticpages_pages_table h on h.HTML_ID = rnc.CONTROLLER_ARGS
			where
				((h.HTML_PAGE_TITLE like '%$search%')
				or (h.HTML_CONTENT like '%$search%'))
				$where_additional";
	echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($result)) {
		return $row['c'];
	}

	return "error";
}

// функция поиска в новостях
function db_search_news($search, $results_on_page, $skip){
	global $news_table_name;
	global $manuka_table;
	global $domain;
	global $root_path;
	global $search_areas;

	if (($results_on_page=="") and ($skip=="")){
		$limit_clause = "";
	}else{
		$limit_clause = "limit $skip, $results_on_page";
	}
	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['news']['restricted_path']) && (count($search_areas['news']['restricted_path']) > 0) ) {
		foreach ($search_areas['news']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.M_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
			n.short_text as content, 
			n.name as title, 
			replace(concat('$domain', '$root_path', m.M_PATH), '*', n.id_new) as path
		from 
			$news_table_name n
			left outer join $manuka_table m on n.category = m.M_INSTANCE
		where
			m.M_COMPONENT = 'news'
			and M_DIRNAME = '*'
			and match (n.name, n.short_text, n.text) AGAINST ('$search')
			$where_additional
		$limit_clause";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	while ($row = mysql_fetch_assoc($result)) {
		$found[] = $row;
	}
	return $found;
}

function db_search_count_news ($search){
	global $news_table_name;
	global $manuka_table;
	global $search_areas;

	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.M_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
				count(n.id_new) as c
			from 
				$news_table_name n
				left outer join $manuka_table m on n.category = m.M_INSTANCE
			where
				m.M_COMPONENT = 'news'
				and M_DIRNAME = '*'
				and match (n.name, n.short_text, n.text) AGAINST ('$search')
				$where_additional";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($result)) {
		return $row['c'];
	}

	return "error";
}

function search_db_htmls_multilang($search, $results_on_page, $skip){
	global $domain;
	global $root_path;
	global $search_areas;
	global $ml_strings_table, $ml_texts_table;
	global $ml_current_language_id;
	global $components_path;

	require("$components_path/staticpages/staticpages_config.php");
	require("$components_path/manuka/manuka_config.php");

	if (($results_on_page=="") and ($skip=="")){
		$limit_clause = "";
	}else{
		$limit_clause = "limit $skip, $results_on_page";
	}
	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.NODE_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
			h.HTML_ID, mlt.LANGUAGE_ID,
			mlt.TEXT_VALUE as content, 
			mls.STRING_VALUE as title, 
			if ( mlt.LANGUAGE_ID != $ml_current_language_id, 
						concat('$domain', '$root_path', m.NODE_PATH, '?lang=', mlt.LANGUAGE_ID),
						concat('$domain', '$root_path', m.NODE_PATH)
			) as path,
			m.NODE_PATH as path_access
		from
			$manuka_controllers_table mc
			left outer join $manuka_r_nodes_controllers_table rnc using (CONTROLLER_ID)
			inner join $manuka_nodes_table m using (NODE_ID)
			left outer join $staticpages_pages_table h on h.HTML_ID = rnc.CONTROLLER_ARGS
			left outer join $ml_strings_table mls on (mls.OBJECT_TYPE = 'pages' and mls.OBJECT_ID = h.HTML_ID and mls.FIELD = 'HTML_PAGE_TITLE')
			left outer join $ml_texts_table mlt on (mlt.LANGUAGE_ID = mls.LANGUAGE_ID and mlt.OBJECT_TYPE = 'pages' and mlt.OBJECT_ID = h.HTML_ID and mlt.FIELD = 'HTML_CONTENT')
		where
			(mc.CONTROLLER_FAMILY = 'staticpages' and mc.CONTROLLER = 'page')
			and
			((mls.STRING_VALUE like '%$search%')
			or (mlt.TEXT_VALUE like '%$search%'))
			$where_additional
		$limit_clause";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	while ($row = mysql_fetch_assoc($result)) {
		$found[] = $row;
	}
	return $found;
}

function search_db_count_htmls_multilang($search){
	global $domain;
	global $root_path;
	global $search_areas;
	global $ml_strings_table, $ml_texts_table;
	global $ml_current_language_id;
	global $components_path;

	require("$components_path/staticpages/staticpages_config.php");
	require("$components_path/manuka/manuka_config.php");

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.NODE_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
				count(HTML_ID) as c
			from 
				$manuka_controllers_table mc
				left outer join $manuka_r_nodes_controllers_table rnc using (CONTROLLER_ID)
				inner join $manuka_nodes_table m using (NODE_ID)
				left outer join $staticpages_pages_table h on h.HTML_ID = rnc.CONTROLLER_ARGS
				left outer join $ml_strings_table mls on (mls.OBJECT_TYPE = 'pages' and mls.OBJECT_ID = h.HTML_ID and mls.FIELD = 'HTML_PAGE_TITLE')
				left outer join $ml_texts_table mlt on (mlt.LANGUAGE_ID = mls.LANGUAGE_ID and mlt.OBJECT_TYPE = 'pages' and mlt.OBJECT_ID = h.HTML_ID and mlt.FIELD = 'HTML_CONTENT')
			where
				(mc.CONTROLLER_FAMILY = 'staticpages' and mc.CONTROLLER = 'page')
				and
				((mls.STRING_VALUE like '%$search%')
				or (mlt.TEXT_VALUE like '%$search%'))
				$where_additional
			";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($result)) {
		return $row['c'];
	}

	return "error";
}

// функция поиска в новостях
function db_search_news_multilang($search, $results_on_page, $skip){
	global $news_table_name;
	global $manuka_table;
	global $domain;
	global $root_path;
	global $search_areas;
	global $ml_strings_table, $ml_texts_table;
	global $ml_current_language_id;

	if (($results_on_page=="") and ($skip=="")){
		$limit_clause = "";
	}else{
		$limit_clause = "limit $skip, $results_on_page";
	}
	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['news']['restricted_path']) && (count($search_areas['news']['restricted_path']) > 0) ) {
		foreach ($search_areas['news']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.M_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
			mlt.TEXT_VALUE as content, 
			mls.STRING_VALUE as title, 
			if ( mlt.LANGUAGE_ID != $ml_current_language_id, 
						concat(replace(concat('$domain', '$root_path', m.M_PATH), '*', n.id_new), '?lang=', mlt.LANGUAGE_ID),
						replace(concat('$domain', '$root_path', m.M_PATH), '*', n.id_new)
			) as path
		from 
			$news_table_name n
			left outer join $manuka_table m on n.category = m.M_INSTANCE
			left outer join $ml_strings_table mls on (mls.OBJECT_TYPE = 'news' and mls.OBJECT_ID = n.id_new and mls.FIELD = 'name')
			left outer join $ml_texts_table mlt on (mlt.LANGUAGE_ID = mls.LANGUAGE_ID and mlt.OBJECT_TYPE = 'news' and mlt.OBJECT_ID = n.id_new and mlt.FIELD = 'text')
		where
			m.M_COMPONENT = 'news'
			and M_DIRNAME = '*'
			and ((mls.STRING_VALUE like '%$search%')
					or 
				(mlt.TEXT_VALUE like '%$search%'))
			$where_additional
		$limit_clause";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	while ($row = mysql_fetch_assoc($result)) {
		$found[] = $row;
	}
	return $found;
}

function db_search_count_news_multilang ($search){
	global $news_table_name;
	global $manuka_table;
	global $search_areas;
	global $ml_strings_table, $ml_texts_table;
	global $ml_current_language_id;

	$search = addslashes($search);

	$where_additional = "";
	if (isset($search_areas['htmls']['restricted_path']) && (count($search_areas['htmls']['restricted_path']) > 0) ) {
		foreach ($search_areas['htmls']['restricted_path'] as $exclude_path) {
			$exclude_path = trim($exclude_path, "/");
			if ($exclude_path != "") {
				$where_additional .= "and m.M_PATH not like '/$exclude_path/%'";
			}
		}
	}

	$query = "select 
				count(n.id_new) as c
			from 
			$news_table_name n
			left outer join $manuka_table m on n.category = m.M_INSTANCE
			left outer join $ml_strings_table mls on (mls.OBJECT_TYPE = 'news' and mls.OBJECT_ID = n.id_new and mls.FIELD = 'name')
			left outer join $ml_texts_table mlt on (mlt.LANGUAGE_ID = mls.LANGUAGE_ID and mlt.OBJECT_TYPE = 'news' and mlt.OBJECT_ID = n.id_new and mlt.FIELD = 'text')
		where
			m.M_COMPONENT = 'news'
			and M_DIRNAME = '*'
			and ((mls.STRING_VALUE like '%$search%')
					or 
				(mlt.TEXT_VALUE like '%$search%'))
			$where_additional";
	//echo "<pre>$query</pre>";
	$result = balo3_db_query($query);
	if (!$result) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($result)) {
		return $row['c'];
	}

	return "error";
}


?>