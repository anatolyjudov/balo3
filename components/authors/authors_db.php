<?php

function authors_db_get_authors_list () {
	global $authors_table_name;

	$qstr = "select
				id,
				sirname,
				name,
				patronymic,
				short_text,
				description
			from 
				$authors_table_name
			order by
				sirname, name, patronymic";

	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);

	if (!$res) {
		return "error";
	}
	
	$_result = array();
	while($_row = mysql_fetch_assoc($res))
	{
		$_result[] = $_row;
	}
	return $_result;
}


function authors_db_get_author_by_id($author_id) {
	global $authors_table_name;

	$qstr = "select
				id,
				sirname,
				name,
				patronymic,
				short_text,
				description
			from 
				$authors_table_name
			where
				id = '$author_id'";
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);

	if (!$res) {
		return "error";
	}

	while($_row = mysql_fetch_assoc($res))
	{
		$_result = $_row;
	}
	return $_result;
}

function authors_db_add_author ($author_info) {
	global $authors_table_name;

	$sirname = addslashes($author_info['sirname']);
	$name = addslashes($author_info['name']);
	$patronymic = addslashes($author_info['patronymic']);
	$short_text = addslashes($author_info['short_text']);
	$description = addslashes($author_info['description']);

			$query = "insert into 
						$authors_table_name
					set
						sirname = '$sirname',
						name = '$name',
						patronymic = '$patronymic',
						short_text = '$short_text',
						description = '$description'";
			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
		if (!$res) {
			return "error";
			}

	return "success";
}

function authors_db_modify_author($author_info) {
	global $authors_table_name;

	$id = $author_info['id'];
	$sirname = addslashes($author_info['sirname']);
	$name = addslashes($author_info['name']);
	$patronymic = addslashes($author_info['patronymic']);
	$short_text = addslashes($author_info['short_text']);
	$description = addslashes($author_info['description']);

	$query = "update
						$authors_table_name
					set
						sirname = '$sirname',
						name = '$name',
						patronymic = '$patronymic',
						short_text = '$short_text',
						description = '$description'
					where
						id = '$id'";
			//echo "<pre>$query</pre>";
		$res = balo3_db_query($query);
		if (!$res) {
			return "error";
			}

	return "success";
}

function authors_db_remove_author ($id) {
	global $authors_table_name;
	global $authors_r_goods_table_name;

	$atn = $authors_table_name;
	$argtn = $authors_r_goods_table_name;

	$res = balo3_db_query("delete from ".$atn." where id = '".$id."'");
	if (!$res) {
		return "error";
		}

	$res = balo3_db_query("delete from ".$argtn." where AUTHOR_ID = '".$id."'");
	if (!$res) {
		return "error";
		}

	return "success";
}

function authors_db_get_authors_for_catalog_good ($good_id) {
	global $authors_table_name;
	global $authors_r_goods_table_name;

	$qstr = "select
				AUTHOR_ID
			from 
				$authors_r_goods_table_name
			where
				GOOD_ID = '$good_id'";
			//echo "<pre>$qstr</pre>";
	$author_id_res = balo3_db_query($qstr);
	if (!$author_id_res) {
		return "error";
		}

	while($_row = mysql_fetch_assoc($author_id_res))
		{
			$author_id[] = $_row;
		}

	 // если авторы еще не были прописаны
	 if (!isset($author_id)) {
	 	$_result = "";
	 	
	 	return $_result;

	 	break;
	 }
	
	foreach ($author_id as $id) {
		if ($id == end($author_id)){
			$query_id .= "id = "."'".$id['AUTHOR_ID']."'";
		} else {
			$query_id .= "id = "."'".$id['AUTHOR_ID']."'"." OR ";
		}
	}
	//show_ar($query_id);

	$qstr = "select
				id,
				sirname,
				name,
				patronymic,
				short_text,
				description
			from 
				$authors_table_name
			where
				$query_id";
	//echo "<pre>$qstr</pre>";
	$res = balo3_db_query($qstr);

	if (!$res) {
		return "error";
	}

	while($_row = mysql_fetch_assoc($res))
	{
		$_result[$_row['id']] = $_row;
	}
	//show_ar($_result);
	return $_result;
}

function authors_db_update_author($good_id, $author_id) {
	global $authors_r_goods_table_name;

	//show_ar($author_id);

	// сначала сбросим старые связи с лотами
	$res = balo3_db_query("delete from ".$authors_r_goods_table_name." where GOOD_ID = '".$good_id."'");
	if (!$res) {
		return "error";
		}

	// если указан один автор
	if ( (count($author_id) == 1) && ($author_id != "") ) {
		$author_id = implode($author_id);
		//var_dump($author_id);
		$query = "insert into 
						$authors_r_goods_table_name
					set
						AUTHOR_ID = '$author_id',
						GOOD_ID = '$good_id'";

			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
		if (!$res) {
			return "error";
			}

		return "success";
	
	// если указаны несколько авторов
	} elseif (count($author_id) > 1) {
		foreach($author_id as $id) {
			$query = "insert into 
						$authors_r_goods_table_name
					set
						AUTHOR_ID = '$id',
						GOOD_ID = '$good_id'";

			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
		if (!$res) {
			return "error";
			}
		}
	}

	return "success";
	

	/*
	//сначала проверим наличие товара в таблице
	$q = "select
			*
		from
			$authors_r_goods_table_name
		where
			GOOD_ID = '$good_id'";
	//echo "<pre>$q</pre>";

	$good_exists_res = balo3_db_query($q);
	if (!$good_exists_res) {
		return "error";
		}

	while($_row = mysql_fetch_assoc($good_exists_res))
		{
			$good_exists = $_row;
		}
	
	//show_ar($good_exists);

	//var_dump((count($good_exists)));

	if ( (count($good_exists)) > 0 ) {

		// если товар есть, то обновляем его автора
		$query = "update
				$authors_r_goods_table_name
			set
				AUTHOR_ID = '$author_id'
			where
				GOOD_ID = '$good_id'";
		//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
		}

return "success";
	
	} else {

	// если такого товара еще нет, то добавляем его в таблицу и прописываем ему автора
	$query = "insert into 
						$authors_r_goods_table_name
					set
						AUTHOR_ID = '$author_id',
						GOOD_ID = '$good_id'";

			//echo "<pre>$query</pre>";
			$res = balo3_db_query($query);
		if (!$res) {
			return "error";
			}

	return "success";
	} */
}

function authors_db_get_goods_id_by_author_id($author_id) {
	global $authors_r_goods_table_name;

	$q = "select
				GOOD_ID
			from
				$authors_r_goods_table_name
			where
				AUTHOR_ID = '$author_id'";
	//echo "<pre>$q</pre>";

	$good_exists_res = balo3_db_query($q);
	if (!$good_exists_res) {
		return "0";
		}

	while($_row = mysql_fetch_assoc($good_exists_res))
		{
			$goods_list[] = $_row;
		}
	
	//show_ar($goods_list);

	return $goods_list;
}


?>