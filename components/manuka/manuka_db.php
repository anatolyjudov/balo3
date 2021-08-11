<?php

/* функция ищет текущую вершину и возвращает информацию о ней */
function manuka_db_get_node_info($str_path) {
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;

	$str_path = addslashes($str_path);

	$query = "select
				m.*
			from
				$manuka_nodes_table m
			where
				(m.PATH_TYPE = 'strict' and m.NODE_PATH = '$str_path')
				or (m.PATH_TYPE = 'mask' and '$str_path' like replace(m.NODE_PATH, '*', '%'))
			order by
				SORT_VALUE desc
			limit 1";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error getting manuka node");
		return "error";
	}

	$nodes = array();
	if ($node_info = mysql_fetch_assoc($res)) {
		foreach($node_info as $k=>$v) {
			$node_info[strtolower($k)] = $v;
		}
		$nodes[$node_info["NODE_ID"]] = $node_info;
	} else {
		return "notfound";
	}

	$node_info["controllers"] = manuka_db_get_node_controllers($node_info['NODE_ID']);
	if ("error" === $node_info["controllers"]) {
		return "error";
	}
	if ("notfound" === $node_info["controllers"]) {
		$node_info["controllers"] = array();
	}

	return $node_info;
}

/* функция возвращает информацию о вершине */
function manuka_db_get_node_info_by_id($node_id) {
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;

	$query = "select
				m.*
			from
				$manuka_nodes_table m
			where
				m.NODE_ID = $node_id
			limit 1";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error getting manuka node");
		return "error";
	}

	$nodes = array();
	if ($node_info = mysql_fetch_assoc($res)) {
		foreach($node_info as $k=>$v) {
			$node_info[strtolower($k)] = $v;
		}
		$nodes[$node_info["NODE_ID"]] = $node_info;
	} else {
		return "notfound";
	}

	$node_info["controllers"] = manuka_db_get_node_controllers($node_info['NODE_ID']);
	if ("error" === $node_info["controllers"]) {
		return "error";
	}
	if ("notfound" === $node_info["controllers"]) {
		$node_info["controllers"] = array();
	}

	return $node_info;
}

/* функция получает информацию о контроллерах указанной вершины */
function manuka_db_get_node_controllers($node_id) {
	global $manuka_nodes_table, $manuka_controllers_table, $manuka_r_nodes_controllers_table;

	$query = "select
				rnc.*, c.*
			from
				$manuka_r_nodes_controllers_table rnc
				left outer join $manuka_controllers_table c using (CONTROLLER_ID)
			where
				rnc.NODE_ID = $node_id";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error getting contollers");
		return "error";
	}

	$controllers = array();
	while ($row = mysql_fetch_assoc($res)) {
		foreach($row as $k=>$v) {
			$row[strtolower($k)] = $v;
		}
		$controllers[$row['PLACEHOLDER']] = $row;
	}

	return $controllers;
}


function manuka_db_add_node($node_path, $path_type, $controller_family, $controller, $layout, $placeholder, $controller_args = '', $custom_data = '') {

	// создадим вершину
	$node_id = manuka_db_create_node($node_path, $path_type, $layout, $custom_data);
	if ("error" == $node_id) {
		return "error";
	}

	// определим id контроллера
	$controller_id = manuka_db_get_controller_id($controller_family, $controller);
	if ("error" == $controller_id) {
		return "error";
	}

	// добавим связь
	$status = manuka_db_create_node_controller_relation($node_id, $controller_id, $placeholder, $controller_args);
	if ("error" == $status) {
		return "error";
	}

	return $node_id;
}


function manuka_db_create_node_controller_relation($node_id, $controller_id, $placeholder, $controller_args) {
	global $manuka_r_nodes_controllers_table;

	$controller_args = addslashes($controller_args);
	$placeholder = addslashes($placeholder);

	$query = "insert into
				$manuka_r_nodes_controllers_table
			(NODE_ID, PLACEHOLDER, CONTROLLER_ID, CONTROLLER_ARGS)
			values
				($node_id, '$placeholder', $controller_id, '$controller_args')";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error creating node to controller relation");
		return "error";
	}

	return "ok";
}


function manuka_db_delete_node_controller_relation($node_id, $placeholder) {
	global $manuka_r_nodes_controllers_table;

	$placeholder = addslashes($placeholder);

	$query = "delete from $manuka_r_nodes_controllers_table where NODE_ID = $node_id and PLACEHOLDER = '$placeholder'";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error creating node to controller relation");
		return "error";
	}

	return "ok";

}

function manuka_db_create_node($node_path, $path_type, $layout, $custom_data, $sort_value = 0) {
	global $manuka_nodes_table;

	$node_path = addslashes($node_path);
	$path_type = addslashes($path_type);
	$layout = addslashes($layout);
	$custom_data = addslashes($custom_data);

	$query = "insert into
				$manuka_nodes_table
				(NODE_PATH, PATH_TYPE, SORT_VALUE, LAYOUT, CUSTOM_DATA)
			values
				('$node_path', '$path_type', $sort_value, '$layout', '$custom_data')";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error creating node");
		return "error";
	}

	return mysql_insert_id();
}

function manuka_db_delete_node($node_id) {
	global $manuka_nodes_table;

	$query = "delete from $manuka_nodes_table where NODE_ID = $node_id";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error creating node");
		return "error";
	}

	return "ok";
}

function manuka_db_get_controller_id($controller_family, $controller) {
	global $manuka_controllers_table;

	// определим номер контроллера, а если его нет - создадим
	$controller_family = addslashes($controller_family);
	$controller = addslashes($controller);
	$query = "select
				c.CONTROLLER_ID
			from
				$manuka_controllers_table c
			where
				c.CONTROLLER_FAMILY = '$controller_family'
				and c.CONTROLLER = '$controller'";
	$res = balo3_db_query($query);
	if (!$res) {
		balo3_error("db error getting controller");
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		$controller_id = $row['CONTROLLER_ID'];
	} else {

		$query2 = "insert into $manuka_controllers_table 
					(CONTROLLER_FAMILY, CONTROLLER)
				values
					('$controller_family', '$controller')";
		$res2 = balo3_db_query($query);
		if (!$res2) {
			balo3_error("db error creating controller");
			return "error";
		}

		$controller_id = mysql_insert_id();

	}

	return $controller_id;
}


?>