<?php

function news_db_get_all_cats() {
	global $news_categories_table_name;


	$qstr = "select
				ntc.id_news_cat,
				ntc.name
			from 
				$news_categories_table_name ntc";
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);
	$_result = array();
	while($_row = mysql_fetch_row($res))
	{
		$_result[] = $_row;
	}
	return $_result;
}

function news_db_get_cats($cid = 0) {
	global $news_categories_table_name;
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;


	$qstr = "select
				ntc.id_news_cat,
				ntc.name,
				m.NODE_ID,
				m.NODE_PATH
			from 
				$news_categories_table_name ntc
				left outer join $manuka_r_nodes_controllers_table rnc on ntc.id_news_cat = rnc.CONTROLLER_ARGS
				inner join $manuka_controllers_table c on (c.CONTROLLER_ID = rnc.CONTROLLER_ID and c.CONTROLLER_FAMILY = 'news')
				inner join $manuka_nodes_table m on (rnc.NODE_ID = m.NODE_ID)
		where
			m.NODE_PATH not like '%*%'";
	//echo "<pre>$qstr</pre>";
	$cid = news_sa($cid);
	if($cid)
	{
		$qstr .= " and ntc.id_news_cat = '".$cid."'";
	}
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);
	$_result = array();
	while($_row = mysql_fetch_row($res))
	{
		$_result[] = $_row;
	}
	return $_result;
}

function news_db_cats_add($uri, $name)
{
	global $manuka_table;
	global $news_categories_table_name;
	global $news_template;

	$ntc = $news_categories_table_name;
	$uri = str_replace('\\', '/', $uri);
	if($uri[0] != '/')
	{
		$uri = '/'.$uri;
	}
	if($uri[strlen($uri) - 1] != '/')
	{
		$uri .= '/';
	}
	$_temp = explode('/', $uri);
	$name_dir = $_temp[count($_temp) - 2];
	$res = balo3_db_query("select NULL from $manuka_table
		where M_PATH = '".$uri."'");
	if(mysql_num_rows($res))
	{
		return "error: existing uri entered";
	}
	array_pop($_temp);
	array_pop($_temp);
	$parent_uri = implode('/', $_temp);
	$parent_uri .= '/';
	$res = balo3_db_query("select
		M_ID,
		M_TREE_LEVEL,
		M_TREE_PATH
		from $manuka_table
		where M_PATH = '".$parent_uri."'");
	if(!mysql_num_rows($res))
	{
		return "error: wrong parent uri";
	}
	if($name == '')
	{
		return "error: empty name";
	}
	balo3_db_query("insert into ".$ntc." set name = '".$name."'");
	$ncid = mysql_insert_id();
	$_temp = mysql_fetch_row($res);
	if($_temp[2] != '')
	{
		$_temp[2] .= ',';
	}
	balo3_db_query("insert into $manuka_table set
		M_PATH = '".$uri."',
		M_DIRNAME = '".$name_dir."',
		M_PARENT = '".$_temp[0]."',
		M_TREE_LEVEL = '".($_temp[1] + 1)."',
		M_TREE_PATH = '".$_temp[2].$_temp[0]."',
		M_COMPONENT = 'news',
		M_INSTANCE = '".$ncid."',
		M_EXECUTIVE = 'show_news.php',
		M_TEMPLATE = '".$news_template."'");
	$m_id = mysql_insert_id();
	balo3_db_query("insert into $manuka_table set
		M_PATH = '".$uri."*/',
		M_DIRNAME = '*',
		M_PARENT = '".$m_id."',
		M_TREE_LEVEL = '".($_temp[1] + 2)."',
		M_TREE_PATH = '".$_temp[2].$_temp[0].",".$m_id."',
		M_COMPONENT = 'news',
		M_INSTANCE = '".$ncid."',
		M_EXECUTIVE = 'show_news.php',
		M_TEMPLATE = '".$news_template."'");
	return "success";
}

function news_db_cats_edit($m_id, $nc_id, $new_uri, $new_name)
{
	global $news_categories_table_name;
	$ntc = $news_categories_table_name;
	$m_id = news_sa($m_id);
	$nc_id = news_sa($nc_id);
	if(!$m_id)
	{
		return "error: id must be positive integer";
	}
	$new_uri = str_replace('\\', '/', $new_uri);
	if($new_uri[0] != '/')
	{
		$new_uri = '/'.$new_uri;
	}
	if($new_uri[strlen($new_uri) - 1] != '/')
	{
		$new_uri .= '/';
	}
	$_tmp = explode('/', $new_uri);
	$new_dirname = $_tmp[count($_tmp) - 2];
	$result = db_update_node($m_id, array('m_dirname' => $new_dirname, 'm_path' => $new_uri));
	if($result != "ok")
	{
		return "error";
	}
	$new_name = addslashes($new_name);
	balo3_db_query("update ".$ntc." set name = '".$new_name."' where id_news_cat = '".$nc_id."'");
	return "success";
}

function news_db_cats_delete($id)
{
	global $news_categories_table_name;
	global $manuka_table;
	$ntc = $news_categories_table_name;
	$id = news_sa($id);
	if(!$id)
	{
		return "error: id must be positive integer";
	}
	balo3_db_query("delete from $manuka_table
		where M_INSTANCE = '".$id."'
		and M_COMPONENT = 'news'");
	balo3_db_query("delete from ".$ntc." where id_news_cat = '".$id."'");
	return "success";
}

function news_db_get_one($id, $visible_all = false, $ml_certain = false) {
	global $news_table_name;
	global $news_categories_table_name;
	global $news_per_page;
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;
	global $ml_multilang_mode, $ml_current_language_id;

	$qstr = "select
		nt.id_new,
		nt.category,
		ntc.name as cat_name,
		nt.name,
		nt.short_text,
		nt.text,
		nt.posted as posted,
		m.NODE_PATH,
		nt.visible,
		nt.picture,
		nt.news_image_foto_id
		from 
			$news_table_name nt,
			$news_categories_table_name ntc
			left outer join $manuka_r_nodes_controllers_table rnc on ntc.id_news_cat = rnc.CONTROLLER_ARGS
			left outer join $manuka_controllers_table c on (c.CONTROLLER_ID = rnc.CONTROLLER_ID and c.CONTROLLER_FAMILY = 'news')
			left outer join $manuka_nodes_table m on (rnc.NODE_ID = m.NODE_ID)
		where 
			nt.category = ntc.id_news_cat
			and id_new = '".$id."'";
	if (!$visible_all){
		$qstr .= " and visible = 1";
	}
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);
	if (!$res) {
		return "error";
	}

	if ($return_data = mysql_fetch_assoc($res)) {
	} else {
		return "notfound";
	}

	if ($ml_multilang_mode) {
		unset($return_data['name']);
		unset($return_data['short_text']);
		unset($return_data['text']);
		if ($ml_certain) {
			$ml_info = db_ml_get_object_info_certain($id, 'news', array($ml_current_language_id, 0));
		} else {
			$ml_info = db_ml_get_object_info($id, 'news');
		}
		if ($ml_info == 'error') {
			return "error";
		}
		foreach($ml_info as $f=>$v) {
			$return_data[$f] = $v;
			/*
			if ($f == 'name') {
				$return_data[3] = $v;
			}
			if ($f == 'short_text') {
				$return_data[4] = $v;
			}
			if ($f == 'text') {
				$return_data[5] = $v;
			}
			*/
		}
	}

	return $return_data;

}

function news_db_get_all_news($page, $cat_id = 0, $visible_all = false) {
	global $news_table_name;
	global $news_categories_table_name;
	global $news_per_page;
	global $ml_multilang_mode, $ml_current_language_id;


	$cat_id = news_sa($cat_id);
	$qstr = "select
		nt.id_new,
		nt.category,
		ntc.name as cat_name,
		nt.name,
		nt.short_text,
		nt.text,
		nt.posted as posted,
		nt.visible,
		nt.picture,
		nt.news_image_foto_id
		from 
			$news_table_name nt,
			$news_categories_table_name ntc
		where 
			nt.category = ntc.id_news_cat";
	if($cat_id) {
		$qstr .= " and category = ".$cat_id;
	}
	if (!$visible_all){
		$qstr .= " and visible = 1";
	}
	$page = news_sa($page);
	$qstr .= " order by posted desc limit ".$page.", ".$news_per_page;
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);
	$_result = array();
	if (!$res) {
		return "error";
	}

	while ($_row = mysql_fetch_assoc($res)) {

		$_result[$_row['id_new']] = $_row;

	}
	

//	show_ar($_result);

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($_result) > 0) ) {

		foreach($_result as &$news_one) {
			unset($news_one['name']); // name
			unset($news_one['short_text']); // short_text
			unset($news_one['text']); // text
		}

		//$meta_strings = db_ml_get_objects_info(array_keys($_result), 'news');
		$meta_strings = db_ml_get_objects_info_certain(array_keys($_result), 'news', array($ml_current_language_id, 0));
		foreach($meta_strings as $news_id => $object_data) {

			foreach($object_data as $f => $v) {
				$_result[$news_id][$f] = $v;
				/*
				if ($f == 'name') {
					$_result[$news_id][3] = $v;
				}
				if ($f == 'short_text') {
					$_result[$news_id][4] = $v;
				}
				if ($f == 'text') {
					$_result[$news_id][5] = $v;
				}
				*/
			}
		}

	}

//	show_ar($_result);
	return $_result;
}

function news_db_get_news($page, $cat_id = 0, $visible_all = false) {
	global $news_table_name;
	global $news_categories_table_name;
	global $news_per_page;
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;
	global $ml_multilang_mode, $ml_current_language_id;


	$cat_id = news_sa($cat_id);
	$qstr = "select
		nt.id_new,
		nt.category,
		ntc.name as cat_name,
		nt.name,
		nt.short_text,
		nt.text,
		nt.posted as posted,
		m.NODE_PATH,
		nt.visible,
		nt.picture,
		nt.news_image_foto_id
		from 
			$news_table_name nt,
			$news_categories_table_name ntc
			left outer join $manuka_r_nodes_controllers_table rnc on ntc.id_news_cat = rnc.CONTROLLER_ARGS
			inner join $manuka_controllers_table c on (c.CONTROLLER_ID = rnc.CONTROLLER_ID and c.CONTROLLER_FAMILY = 'news')
			inner join $manuka_nodes_table m on (rnc.NODE_ID = m.NODE_ID)
		where 
			nt.category = ntc.id_news_cat
			and m.NODE_PATH not like '%*%'";
	if($cat_id) {
		$qstr .= " and category = ".$cat_id;
	}
	if (!$visible_all){
		$qstr .= " and visible = 1";
	}
	$page = news_sa($page);
	$qstr .= " order by posted desc limit ".$page.", ".$news_per_page;
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);
	$_result = array();
	if (!$res) {
		return "error";
	}

	while ($_row = mysql_fetch_assoc($res)) {

		$_result[$_row['id_new']] = $_row;

	}
	

//	show_ar($_result);

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($_result) > 0) ) {

		foreach($_result as &$news_one) {
			unset($news_one['name']); // name
			unset($news_one['short_text']); // short_text
			unset($news_one['text']); // text
		}

		//$meta_strings = db_ml_get_objects_info(array_keys($_result), 'news');
		$meta_strings = db_ml_get_objects_info_certain(array_keys($_result), 'news', array($ml_current_language_id, 0));
		foreach($meta_strings as $news_id => $object_data) {

			foreach($object_data as $f => $v) {
				$_result[$news_id][$f] = $v;
				/*
				if ($f == 'name') {
					$_result[$news_id][3] = $v;
				}
				if ($f == 'short_text') {
					$_result[$news_id][4] = $v;
				}
				if ($f == 'text') {
					$_result[$news_id][5] = $v;
				}
				*/
			}
		}

	}

//	show_ar($_result);
	return $_result;
}

// get news with no manuka joining
function news_db_get_last_news($limit = "", $news_cat = "", $visible_all = false) {
	global $news_table_name;
	global $news_categories_table_name;
	global $news_per_page;
	global $ml_multilang_mode, $ml_current_language_id;

	$where_additional = "";
	if ($news_cat != "") {
		if (preg_match("/^\d+$/", $news_cat)) {
			$where_additional = "and ntc.id_news_cat = $news_cat";
		}
		if (preg_match("/^[\d\s,]+$/", $news_cat)) {
			$where_additional = "and ntc.id_news_cat in ($news_cat)";
		}
	}

	if (!$visible_all){
		$where_additional .= " and nt.visible = 1";
	}

	$limitclause = "";
	if ($limit != "") {
		$limitclause = "limit $limit";
	}

	$query = "SELECT
				nt.id_new,
				nt.category,
				ntc.name as catname,
				nt.name,
				nt.short_text,
				nt.text,
				nt.posted as posted,
				nt.visible,
				nt.picture,
				nt.news_image_foto_id
			from
				$news_table_name nt,
				$news_categories_table_name ntc
			where
				nt.category = ntc.id_news_cat
				$where_additional

			ORDER BY
				nt.posted DESC
			$limitclause";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		return array();
	}

	$_result = array();
	while($row = mysql_fetch_assoc($res)) {
		$_result[$row['id_new']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($_result) > 0) ) {

		foreach($_result as &$news_one) {
			unset($news_one['name']);
			unset($news_one['short_text']);
			unset($news_one['text']);
		}

		//$meta_strings = db_ml_get_objects_info(array_keys($_result), 'news');
		$meta_strings = db_ml_get_objects_info_certain(array_keys($_result), 'news', array($ml_current_language_id, 0));

		foreach($meta_strings as $news_id => $object_data) {
			foreach($object_data as $f => $v) {
				$_result[$news_id][$f] = $v;
			}
		}

	}

	return $_result;

}

function news_db_get_news_widget($limit = "", $news_cat = "", $visible_all = false) {
	global $news_table_name;
	global $news_categories_table_name;
	global $news_per_page;
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;
	global $ml_multilang_mode, $ml_current_language_id;

	$where_additional = "";
	if ($news_cat != "") {
		if (preg_match("/^\d+$/", $news_cat)) {
			$where_additional = "and ntc.id_news_cat = $news_cat";
		}
		if (preg_match("/^[\d\s,]+$/", $news_cat)) {
			$where_additional = "and ntc.id_news_cat in ($news_cat)";
		}
	}

	if (!$visible_all){
		$where_additional .= " and nt.visible = 1";
	}

	$limitclause = "";
	if ($limit != "") {
		$limitclause = "limit $limit";
	}

	$query = "SELECT
				nt.id_new,
				nt.category,
				ntc.name as catname,
				nt.name,
				nt.short_text,
				nt.text,
				nt.posted as posted,
				nt.visible,
				m.NODE_PATH,
				nt.picture,
				nt.news_image_foto_id
			from
				$news_table_name nt,
				$news_categories_table_name ntc
				left outer join $manuka_r_nodes_controllers_table rnc on ntc.id_news_cat = rnc.CONTROLLER_ARGS
				inner join $manuka_controllers_table c on (c.CONTROLLER_ID = rnc.CONTROLLER_ID and c.CONTROLLER_FAMILY = 'news')
				inner join $manuka_nodes_table m on (rnc.NODE_ID = m.NODE_ID)
			where
				nt.category = ntc.id_news_cat
				and m.NODE_PATH not like '%*%'
				$where_additional

			ORDER BY
				nt.posted DESC
			$limitclause";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		return array();
	}

	$_result = array();
	while($row = mysql_fetch_assoc($res)) {
		$_result[$row['id_new']] = $row;
	}

	// в режиме мультиязычности достаем все мультиязычные поля для найденного
	if ( ($ml_multilang_mode) && (count($_result) > 0) ) {

		foreach($_result as &$news_one) {
			unset($news_one['name']);
			unset($news_one['short_text']);
			unset($news_one['text']);
		}

		//$meta_strings = db_ml_get_objects_info(array_keys($_result), 'news');
		$meta_strings = db_ml_get_objects_info_certain(array_keys($_result), 'news', array($ml_current_language_id, 0));

		foreach($meta_strings as $news_id => $object_data) {
			foreach($object_data as $f => $v) {
				$_result[$news_id][$f] = $v;
			}
		}

	}

	return $_result;

}



function news_db_create($name, $cid, $text, $stext, $news_date, $news_picture, $news_image_foto_id) {
	global $news_table_name;
	global $news_categories_table_name;
	global $ml_multilang_mode;

	$ntc = $news_categories_table_name;
	$nt = $news_table_name;

	if ($news_image_foto_id != '') {
		$image_foto_id_val = "'$news_image_foto_id'";
	} else {
		$image_foto_id_val = "NULL";
	}

	$cid = news_sa($cid);
	if(!$cid) {
		return "error: cat_id must be positive integer";
	}

	$res = balo3_db_query("select NULL from ".$ntc." where id_news_cat = ".$cid);
	if(!mysql_num_rows($res)) {
		return "error: category does not exist";
	}

	if (preg_match("/^(\d+)\.(\d+)\.(\d+)$/", trim($news_date), $matches)) {
		$news_date = "'".$matches[3] . "-" . $matches[2] . "-" . $matches[1]."'";
	} else {
		$news_date = "NOW()";
	}
	$news_picture = addslashes($news_picture);

	if ($ml_multilang_mode) {

		$query = "insert into 
					$nt
			set
				category = '$cid',
				picture = '$news_picture',
				posted = $news_date,
				news_image_foto_id = $image_foto_id_val";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$news_id = mysql_insert_id();

		$status = db_ml_save_object_strings($news_id, 'news', 
			array(
				'name' => $name
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($news_id, 'news', 
			array(
				'short_text' => $stext,
				'text' => $text
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$name = addslashes($name);
		$text = addslashes($text);
		$stext = addslashes($stext);

		$query = "insert into 
					$nt
				set
					category = '$cid',
					name = '$name',
					posted = $news_date,
					picture = '$news_picture',
					news_image_foto_id = $image_foto_id_val,
					short_text = '$stext',
					text = '$text'";
		$res = balo3_db_query();
		if (!$res) {
			return "error";
		}

	}

	return "success";
}

function news_db_edit($id, $new_cid, $new_name, $new_text, $new_stext, $news_date, $news_picture, $news_image_foto_id) {
	global $news_table_name;
	global $news_categories_table_name;
	global $ml_multilang_mode;

	$ntc = $news_categories_table_name;
	$nt = $news_table_name;
	$id = news_sa($id);
	if(!$id) {
		return "error: id must be positive integer";
	}

	$new_cid = news_sa($new_cid);
	if(!$new_cid) {
		return "error: cat_id must be positive integer";
	}

	if ($news_image_foto_id != '') {
		$image_foto_id_val = "'$news_image_foto_id'";
	} else {
		$image_foto_id_val = "NULL";
	}

	$res = balo3_db_query("select NULL from ".$ntc." where id_news_cat = ".$new_cid);
	if(!mysql_num_rows($res))
	{
		return "error: category does not exist";
	}

	$news_picture = addslashes($news_picture);

	if (preg_match("/^(\d+)\.(\d+)\.(\d+)$/", trim($news_date), $matches)) {
		$news_date = $matches[3] . "-" . $matches[2] . "-" . $matches[1];
		$nadd = ", posted = '$news_date 00:00:00'";
	}

	if ($ml_multilang_mode) {

		$query = "update 
					$nt
			set
				category = '$new_cid',
				picture = '$news_picture',
				news_image_foto_id = $image_foto_id_val
				$nadd
			where 
				$nt.id_new = '$id'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}

		$status = db_ml_save_object_strings($id, 'news', 
			array(
				'name' => $new_name
			));
		if ($status === "error") {
			return "error";
		}

		$status = db_ml_save_object_texts($id, 'news', 
			array(
				'short_text' => $new_stext,
				'text' => $new_text
			));
		if ($status === "error") {
			return "error";
		}

	} else {

		$new_name = addslashes($new_name);
		$new_text = addslashes($new_text);
		$new_stext = addslashes($new_stext);

		$query = "update 
					$nt
				set
					category = '$new_cid',
					name = '$new_name',
					short_text = '$new_stext',
					picture = '$news_picture',
					news_image_foto_id = $image_foto_id_val,
					text = '$new_text'
					$nadd
				where 
					$nt.id_new = '$id'";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
		}
	}


	return "success";
}

function news_db_delete($id) {
	global $news_table_name;
	global $ml_multilang_mode;

	$nt = $news_table_name;
	$id = news_sa($id);
	if(!$id) {
		return "error: id must be positive integer";
	}

	if ($ml_multilang_mode) {

		$status = db_ml_remove_object_info($id, 'news');
		if ($status === "error") {
			return "error";
		}

	}

	$res = balo3_db_query("delete from ".$nt." where id_new = '".$id."'");

	return "success";
}



function news_db_public($id) {
	global $news_table_name;
	$nt = $news_table_name;
	$id = news_sa($id);
	if(!$id)
	{
		return "error: id must be positive integer";
	}
	balo3_db_query("update ".$nt." set
		visible = not(visible)
		where ".$nt.".id_new = '".$id."'");
	return "success";
}


?>