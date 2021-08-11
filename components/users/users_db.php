<?php

function users_db_get_user_info($login) {

	return users_db_get_user($login);

}


function users_db_get_user($login) {
	global $users_users_table;

	if (preg_match("/^\d+$/", $login)) {
		$whereclause = "USER_ID = $login";
	} else {
		$whereclause = "USER_NAME = '$login'";
	}

	$query = "select * from $users_users_table where $whereclause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row;
	}

	return "notfound";
}


function users_db_get_roles() {
	global $users_users_table;
	global $users_roles_table;
	global $users_users_roles_table;

	$query = "select 
				r.*, 
				u.USER_ID, u.USER_NAME 
			from 
				$users_roles_table r 
				left outer join $users_users_roles_table ur using (ROLE_ID)
				left outer join $users_users_table u using (USER_ID)
			order by 
				r.ROLE_NAME, u.USER_NAME";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$arr = array();
	while ($row = mysql_fetch_assoc($res)) {
		if (!array_key_exists($row['ROLE_ID'], $arr)) {
			$arr[$row['ROLE_ID']] = $row;
			unset($arr[$row['ROLE_ID']]['USER_ID']);
			unset($arr[$row['ROLE_ID']]['USER_NAME']);
			$arr[$row['ROLE_ID']]['users'] = array();
		}
		if ($row['USER_ID']!="") {
			$arr[$row['ROLE_ID']]['users'][$row['USER_ID']]['USER_ID'] = $row['USER_ID'];
			$arr[$row['ROLE_ID']]['users'][$row['USER_ID']]['USER_NAME'] = $row['USER_NAME'];
		}
	}

	return $arr;
}

function users_db_get_roles_names() {
	global $users_roles_table;

	$query = "select r.* from $users_roles_table r order by ROLE_NAME";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$arr = array();
	while ($row = mysql_fetch_assoc($res)) {
		$arr[$row['ROLE_ID']] = $row;
	}

	return $arr;
}

function users_db_get_actions() {
	global $users_actions_table;

	$query = "select a.* from $users_actions_table a order by ACTION_NAME";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$arr = array();
	while ($row = mysql_fetch_assoc($res)) {
		$arr[$row['ACTION_ID']] = $row;
	}

	return $arr;
}

function users_db_add_role($role_name) {
	global $users_roles_table;

	$query = "insert into $users_roles_table (ROLE_NAME) values ('$role_name')";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return mysql_insert_id();
}

function users_db_remove_role($role_id) {
	global $users_roles_table;
	global $users_users_roles_table;
	global $users_roles_actions_table;

	// все пользователи роли
	$query = "delete from $users_users_roles_table where ROLE_ID = $role_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	// все действия роли
	$query = "delete from $users_roles_actions_table where ROLE_ID = $role_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	// сама роль
	$query = "delete from $users_roles_table where ROLE_ID = $role_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function users_db_assign_role($user_id, $role_id) {
	global $users_users_roles_table;

	$query = "insert into $users_users_roles_table (USER_ID, ROLE_ID) values ($user_id, $role_id)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function users_db_deassign_role($user_id, $role_id) {
	global $users_users_roles_table;

	$query = "delete from $users_users_roles_table where USER_ID = $user_id and ROLE_ID = $role_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function users_db_assign_rights($role_id, $action_id, $path) {
	global $users_roles_actions_table;

	$path = addslashes($path);

	$query = "insert into $users_roles_actions_table (ROLE_ID, ACTION_ID, AFFECTED_BRANCH) values ($role_id, $action_id, '$path')";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function users_db_modify_role($role_id, $role_name) {
	global $users_roles_table;

	$role_name = addslashes($role_name);

	$query = "update $users_roles_table set ROLE_NAME = '$role_name' where ROLE_ID = $role_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";

}

function users_db_get_branches($role_id = 0, $action_id = 0) {
	global $users_roles_actions_table;

	$query = "select * from $users_roles_actions_table where ROLE_ID = $role_id and ACTION_ID = $action_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$arr = array();
	$num = 0;
	while ($row = mysql_fetch_assoc($res)) {
		$arr[$num] = $row;
		$num++;
	}

	return $arr;
}

function users_db_update_role_actions($role_id, $action_id, $new_rights) {
	global $users_roles_actions_table;

	// удалим старое
	$query = "delete from $users_roles_actions_table where ROLE_ID = $role_id and ACTION_ID = $action_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	// составим новое
	$query = "insert into $users_roles_actions_table (ROLE_ID, ACTION_ID, AFFECTED_BRANCH, RIGHT_TYPE) values ";

	$query_values = "";

	foreach($new_rights as $k=>$v) {
		$query_values .= "($role_id, $action_id, '" . $v['affected_branch'] . "', " . $v['right_type'] . "),";
	}

	if ($query_values == "") {
		return "ok";
	} else {
		$query_values = substr($query_values, 0, -1);
	}

	$query .= $query_values;
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function users_db_new_branch($role_id, $action_id, $branch, $right_type) {
	global $users_roles_actions_table;

	$query = "insert into $users_roles_actions_table (ROLE_ID, ACTION_ID, AFFECTED_BRANCH, RIGHT_TYPE) values ($role_id, $action_id, '$branch', $right_type)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";


}

function users_db_get_users_info($user_ids) {
	global $users_users_table;
	
	$query = "select 
				cu.USER_ID, cu.USER_NAME, cu.AUTH_TYPE,
				cu.USER_NICKNAME, cu.USER_REAL_NAME, cu.USER_REAL_NAME_FLG, cu.USER_AVATAR, cu.USER_EMAIL, cu.USER_EMAIL_FLG, cu.USER_ICQ, cu.USER_ICQ_FLG
			from 
				$users_users_table cu
			where 
				USER_ID in ($user_ids)";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$users_list = array();
	while($row = mysql_fetch_assoc($res)) {
		$users_list[$row['USER_ID']] = $row;
	}

	return $users_list;
}

function users_db_get_users_list($skip = 0, $limit = 0) {
	global $users_users_table;

	if ($skip != 0) {
		$limitclause = "limit $skip, $limit";
	} elseif ($limit != 0) {
		$limitclause = "limit $limit";
	} else {
		$limitclause = "";
	}

	$query = "select
				*
			from
				$users_users_table
			order by
				USER_ID
			$limitclause";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$users_list = array();
	while ($row = mysql_fetch_assoc($res)) {
		$users_list[$row['USER_ID']] = $row;
	}

	return $users_list;
}

function users_db_count_users() {
	global $users_users_table;

	$query = "select count(USER_ID) as c from $users_users_table";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row['c'];
	}

	return "error";
}

function users_db_modify_user($user_id, $user_name, $auth_type = 'db', $user_password = '', $user_nickname = '', $user_real_name = '', $user_email = '', $user_icq = '', $user_phone = '', $user_address = '', $user_phone_flg = 1, $user_address_flg = 1, $active = 0) {
	global $users_users_table;

	$user_name = addslashes($user_name);
	$user_password = addslashes($user_password);
	$user_nickname = addslashes($user_nickname);
	if ($user_nickname == "") {
		$user_nickname = $user_name;
	}
	$user_real_name = addslashes($user_real_name);
	$user_email = addslashes($user_email);
	$user_icq = addslashes($user_icq);
	$user_phone = addslashes($user_phone);	
	$user_address = addslashes($user_address);	

	if ($user_password == "") {
		$query = "update 
					$users_users_table 
				set
					USER_NAME = '$user_name',
					AUTH_TYPE = '$auth_type',
					USER_NICKNAME = '$user_nickname',
					USER_REAL_NAME = '$user_real_name',
					USER_EMAIL = '$user_email',
					USER_ICQ = '$user_icq',
					USER_PHONE = '$user_phone', 
					USER_ADDRESS = '$user_address',
					ACTIVE = '$active'
				where
					USER_ID = $user_id";
	} else {
		$query = "update 
					$users_users_table 
				set
					USER_NAME = '$user_name',
					AUTH_TYPE = '$auth_type',
					USER_PASSWORD = md5('$user_password'),
					USER_NICKNAME = '$user_nickname',
					USER_REAL_NAME = '$user_real_name',
					USER_EMAIL = '$user_email',
					USER_ICQ = '$user_icq',
					USER_PHONE = '$user_phone', 
					USER_ADDRESS = '$user_address',
					ACTIVE = '$active'
				where
					USER_ID = $user_id";
	}
	
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function users_db_remove_user($user_id) {
	global $users_users_table, $users_sessions_table;

	$query = "delete from $users_sessions_table where USER_ID = $user_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$query = "delete from $users_users_table where USER_ID = $user_id";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	return "ok";
}

function users_db_recovery_pass($something){
	global $users_users_table;
	
	$something = addslashes($something);
	
	$query = "select USER_ID, USER_EMAIL 
				from $users_users_table 
				where USER_NAME='$something' or USER_EMAIL='$something'"; 
				
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}		
	
	if($row = mysql_fetch_assoc($res)) {
		return $row;
	}
	return 'notfound';
}

function users_db_send_new_pass_for_email($user_id, $user_email) {
	global $users_users_table;
	global $domain;
 
	$new_pass = md5(mt_rand()); 
	
	$query = "update $users_users_table set USER_PASSWORD='$new_pass' where USER_ID='$user_id'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}	
	mail($user_email, 'Восстановление пароля', 'Новый пароль: '.$new_pass.'\n'.$domain);	
}

function users_db_email_exists($email){
	global $users_users_table; 
	
	$query = "select USER_ID from $users_users_table where USER_EMAIL='$email'";	
	
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}
	
	if($row = mysql_fetch_assoc($res)){
		return 1;
	}  
	return 0;
}

function users_db_activate_user($aid){
	global $users_users_table; 
	
	$query = "update $users_users_table set ACTIVE='1' where ACTIVATION_ID='$aid'";
	
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}
	if(mysql_affected_rows() == 0){
		return "notfound";
	}
}

function users_db_delete_notactivated(){
	global $users_users_table; 
	global $users_noactivated_days;
	
	$query = "delete 
				from
			$users_users_table
				where 
					REG_DATE < NOW() - INTERVAL $users_noactivated_days DAY
					and ACTIVE='0'
					and USER_ID != -1";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}
}

function users_db_nickname_exists($nickname) {
	global $users_users_table;

	$nickname = addslashes($nickname);

	$query = "select USER_ID from $users_users_table where USER_NICKNAME = '$nickname'";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return true;
	}

	return false;
}

function users_db_create_user($user_name, $auth_type = 'ldap', $user_password = '', $user_nickname = '', $user_real_name = '', $user_email = '', $user_icq = '', $user_phone = '', $user_address = '', $user_real_name_flg = 1, $user_email_flg = 1, $user_icq_flg = 1, $user_phone_flg = 1, $user_address_flg = 1, $activation_id = '', $active = 0) {
	global $users_users_table;

	$user_name = addslashes($user_name);
	$user_password = addslashes($user_password);
	$user_nickname = addslashes($user_nickname);
	if ($user_nickname == "") {
		$user_nickname = $user_name;
	}
	$user_real_name = addslashes($user_real_name);
	$user_email = addslashes($user_email);
	$user_icq = addslashes($user_icq);
	$user_phone = addslashes($user_phone);	
	$user_address = addslashes($user_address);

	if (($user_real_name_flg != 1) && ($user_real_name_flg != 0)) $user_real_name_flg = 1;
	if (($user_email_flg != 1) && ($user_email_flg != 0)) $user_email_flg = 1;
	if (($user_icq_flg != 1) && ($user_icq_flg != 0)) $user_icq_flg = 1;
	if (($user_phone_flg != 1) && ($user_phone_flg != 0)) $user_phone_flg = 1;	
	if (($user_address_flg != 1) && ($user_address_flg != 0)) $user_address_flg = 1;	

	if ($user_password == "") {
		$user_password_string1 = "";
		$user_password_string2 = "";
	} else {
		$user_password_string1 = "USER_PASSWORD,";
		$user_password_string2 = "md5('$user_password'),";
	}

	$query = "insert into $users_users_table 
				(USER_NAME, AUTH_TYPE, $user_password_string1 USER_NICKNAME, USER_REAL_NAME, USER_EMAIL, USER_ICQ, USER_REAL_NAME_FLG, USER_EMAIL_FLG, USER_ICQ_FLG, USER_PHONE, USER_PHONE_FLG, USER_ADDRESS, USER_ADDRESS_FLG, ACTIVATION_ID, REG_DATE, ACTIVE)
		values ('$user_name', '$auth_type', $user_password_string2 '$user_nickname', '$user_real_name', '$user_email', '$user_icq', $user_real_name_flg, $user_email_flg, $user_icq_flg, '$user_phone', '$user_phone_flg', '$user_address','$user_address_flg', '$activation_id', NOW(), '$active')";
	$res = balo3_db_query($query);
	if (!$res) {
		return "error";
	}

	$user_id = mysql_insert_id();

	return $user_id;
}


function users_db_get_guest_name() {
	global $users_users_table;

	$query = "select USER_NAME from $users_users_table where USER_ID = -1";
	$res = balo3_db_query($query);
	if (!$res) {
		return "guest";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row['USER_NAME'];
	}

	return "guest";
}



function users_db_check_rights($user_id, $action_name, $branch = "") {
	global $users_roles_actions_table, $users_users_roles_table, $users_actions_table, $users_roles_table, $users_users_table;

	if ($branch != "") {
		$where_additional = "and POSITION(ra.AFFECTED_BRANCH in '$branch') = 1";
	}

	$query = "select 
				a.ACTION_NAME, ra.AFFECTED_BRANCH, BIT_OR(ra.RIGHT_TYPE) as RIGHT_STATE
			from
				$users_users_table u
				left outer join $users_users_roles_table ur using (USER_ID)
				left outer join $users_roles_actions_table ra using (ROLE_ID)
				inner join $users_actions_table a using (ACTION_ID)
			where
				(u.USER_ID = $user_id or u.USER_ID = -1) and
				a.ACTION_NAME = '$action_name'
				$where_additional
			group by 
				ra.AFFECTED_BRANCH
			order by 
				ra.AFFECTED_BRANCH desc
			limit 1";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		if ($row['RIGHT_STATE'] == 1) {
			return true;
		} else {
			return false;
		}
	}

	return false;

}

function users_db_get_multiple_rights($user_id, $actions_names, $branch = "") {
	global $users_roles_actions_table, $users_users_roles_table, $users_actions_table, $users_roles_table, $users_users_table;

	if ($branch!="") {
		$where_additional = "and ((POSITION(ra.AFFECTED_BRANCH in '$branch') = 1) or (ra.AFFECTED_BRANCH like '$branch%'))";
	} else {
		$where_additional = "";
	}

	$actions_names_array = explode(",", $actions_names);
	$actions_names = "";
	foreach ($actions_names_array as $v) $actions_names .= "'$v',";
	if ($actions_names == "") 
		return "error";

	$actions_names = substr($actions_names, 0, -1);

	$query = "select 
				a.ACTION_NAME, ra.AFFECTED_BRANCH, BIT_OR(ra.RIGHT_TYPE) as RIGHT_STATE
			from
				$users_users_table u
				left outer join $users_users_roles_table ur using (USER_ID)
				left outer join $users_roles_actions_table ra using (ROLE_ID)
				inner join $users_actions_table a using (ACTION_ID)
			where
				(u.USER_ID = $user_id or u.USER_ID = -1)
				and a.ACTION_NAME in ($actions_names)
				$where_additional
			group by 
				a.ACTION_NAME, 
				ra.AFFECTED_BRANCH";

	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	$arr = array();
	while ($row = mysql_fetch_assoc($res)) {
		$arr[$row['ACTION_NAME']][$row['AFFECTED_BRANCH']] = $row['RIGHT_STATE'];
	}

	return $arr;
}

function users_db_get_node_multiple_rights($user_id, $actions_names, $branch) {
	global $users_roles_actions_table, $users_users_roles_table, $users_actions_table, $users_roles_table, $users_users_table;

	if ($branch!="") {
		$where_additional = "and (POSITION(ra.AFFECTED_BRANCH in '$branch') = 1)";
	} else {
		$where_additional = "";
	}

	$actions_names_array = explode(",", $actions_names);
	$actions_names = "";
	foreach ($actions_names_array as $v) $actions_names .= "'$v',";
	if ($actions_names == "") 
		return "error";

	$actions_names = substr($actions_names, 0, -1);

	$query = "select 
				a.ACTION_NAME, ra.AFFECTED_BRANCH, BIT_OR(ra.RIGHT_TYPE) as RIGHT_STATE
			from
				$users_users_table u
				left outer join $users_users_roles_table ur using (USER_ID)
				left outer join $users_roles_actions_table ra using (ROLE_ID)
				inner join $users_actions_table a using (ACTION_ID)
			where
				(u.USER_ID = $user_id or u.USER_ID = -1)
				and a.ACTION_NAME in ($actions_names)
				$where_additional
			group by 
				a.ACTION_NAME, 
				ra.AFFECTED_BRANCH";

	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	$arr = array();
	while ($row = mysql_fetch_assoc($res)) {
		$arr[$row['ACTION_NAME']][$row['AFFECTED_BRANCH']] = $row['RIGHT_STATE'];
	}

	return $arr;
}


// функция достает информацию о текущей сессии вместе с информацией о залогиненном пользователе
// она работает с общей базой данных системы веб-ресурсов
function users_db_get_session_info($session_id) {
	global $users_sessions_table, $users_users_table;

	if ($users_users_table == "") {
		$query = "select 
					*, INET_NTOA(SESSION_IP) as SESSION_IP,
					(UNIX_TIMESTAMP(LAST_VISIT) - UNIX_TIMESTAMP()) as TIME_LAG
				from 
					$users_sessions_table 
				where 
					SESSION_ID_MD5 = '$session_id'";
	} else {
		$query = "select *, INET_NTOA(SESSION_IP) as SESSION_IP,
					(UNIX_TIMESTAMP(LAST_VISIT) - UNIX_TIMESTAMP()) as TIME_LAG
				from 
					$users_sessions_table 
					inner join $users_users_table using (USER_ID) 
				where 
					SESSION_ID_MD5 = '$session_id'";
	}
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row;
	}

	return "notfound";
}

// функция возвращает информацию о текущей сессии в том же формате, что и db_get_session_info(), но для гостя
// т.е., по сути дела никакой сессии при этом нет, но мы получаем всю информацию о гостевом аккаунте
function users_db_get_guest_session_info() {
	global $users_sessions_table, $users_users_table;

	$query = "select 
				cu.USER_ID, cu.USER_NAME, cu.AUTH_TYPE,
				cu.USER_NICKNAME, cu.USER_REAL_NAME, cu.USER_REAL_NAME_FLG, cu.USER_AVATAR, cu.USER_EMAIL, cu.USER_EMAIL_FLG, cu.USER_ICQ, cu.USER_ICQ_FLG,
				cs.SESSION_ID, cs.SESSION_ID_MD5, cs.LAST_VISIT, cs.SESSION_IP, 0 as TIME_LAG
			from 
				$users_users_table cu
				left outer join $users_sessions_table cs using (USER_ID)
			where 
				cu.USER_ID = -1";

	//echo $query;
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	if ($row = mysql_fetch_assoc($res)) {
		return $row;
	}

	return "notfound";

}

function users_db_update_session_last_visit($session_id) {
	global $users_sessions_table;

	$query = "update $users_sessions_table set LAST_VISIT = NOW() where SESSION_ID_MD5 = '$session_id'";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return "ok";
}

function users_db_update_session_fields($session_id, $adfields) {
	global $users_sessions_table;

	if (count($adfields) == 0) return "ok";

	$set_clause = "";
	foreach($adfields as $f=>$v) {
		$set_clause .= "$f = '$v', ";
	}
	$set_clause = substr($set_clause, 0, -2);

	$query = "update 
				$users_sessions_table 
			set
				$set_clause
			where
				SESSION_ID = $session_id";
	//echo "<pre>$query</pre>";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return "ok";
}

function users_db_remove_old_sessions() {
	global $users_sessions_table, $users_session_lifetime;

	$query = "delete from $users_sessions_table where (UNIX_TIMESTAMP() - UNIX_TIMESTAMP(LAST_VISIT)) > $users_session_lifetime";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return "ok";
}

function users_db_create_session($user_id, $ip) {
	global $users_sessions_table;

	$query = "insert into $users_sessions_table (LAST_VISIT, SESSION_IP, USER_ID) values (NOW(), INET_ATON('$ip'), $user_id)";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	$session_id = mysql_insert_id();
	$session_id_md5 = md5($session_id);

	$query = "update $users_sessions_table set SESSION_ID_MD5 = '$session_id_md5' where SESSION_ID = $session_id";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return md5($session_id);
}

function users_db_destroy_session($session_id) {
	global $users_sessions_table;

	$query = "delete from $users_sessions_table where SESSION_ID_MD5 = '$session_id'";
	$res = balo3_db_query($query);
	if (!$res) {
		// обработка ошибки
		return "error";
	}

	return "ok";
}




?>