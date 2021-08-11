<?php

function users_init_session() {
	global $users_session_info, $users_session_cookie_name, $users_session_lifetime, $auth_cookie_domain;
	global $root_path;
	global $smarty;

	$cookie_ok = false;

	if (isset($_COOKIE[$users_session_cookie_name])) {
		$given_session_id = $_COOKIE[$users_session_cookie_name];
	} elseif (isset($_POST['session_id'])) {
		$given_session_id = $_POST['session_id'];
	} else {
		$given_session_id = "";
	}

	if ($given_session_id != "") {
		// если есть кукис, то его надо проверять
		// достанем инфу о сессии и о пользователе
		$users_session_info = users_db_get_session_info($given_session_id);
		if ($users_session_info == "error") {
			// сообщение об ошибке
			echo "error when init_session1";
			return;
		}
		// кукис выживет только если:
		// найдена сессия с нужным id, в ней указан правильный ip-адрес, а также срок жизни сессии ещё не истёк
		if ($users_session_info == "notfound") {
			// для данного кукиса не найдена сессия - убиваем его
			setcookie($users_session_cookie_name, "", time() - 3600, $root_path."/", ".$auth_cookie_domain");
			$cookie_ok = false;
		} else {
			if ( ($users_session_info['SESSION_IP'] == $_SERVER['REMOTE_ADDR']) && ($users_session_info['TIME_LAG'] < $users_session_lifetime)) {
				// обновлять LAST_VISIT (update)
				$status = users_db_update_session_last_visit($users_session_info['SESSION_ID_MD5']);
				if ($status == "error") {
					// сообщение об ошибке
					echo "error when init_session2";
					return;
				}
				// продлевается кукис
				setcookie($users_session_cookie_name, $given_session_id, time() + $users_session_lifetime, $root_path."/", ".$auth_cookie_domain");
				// удалять старые сессии (delete)
				$status = users_db_remove_old_sessions();
				if ($status == "error") {
					// сообщение об ошибке
					echo "error when init_session3";
					return;
				}
				$cookie_ok = true;
			} else {
				// для данного кукиса найдена сессия, но в ней некорректная информация, или срок её жизни истёк
				// убиваем кукис
				setcookie($users_session_cookie_name, "", time() - 3600, $root_path."/", ".$auth_cookie_domain");
				// убиваем сессию
				if (isset($users_session_info['SESSION_ID_MD5'])) {
					$status = users_db_destroy_session($session_info['SESSION_ID_MD5']);
					if ($status == "error") {
						return "error";
					}
				}
				$cookie_ok = false;
			}
		}
	}
	
	if (!$cookie_ok) {
		users_create_session(-1);
		//	мы попадаем сюда, если нет кукиса, либо с ним было что-то не в порядке (ip неверный, или срок истек) и мы его уже грохнули
		//	в инфу о сессии писать пустые значения
		$users_session_info = users_db_get_guest_session_info();
		//echo"<pre>";print_r($session_info);echo"</pre>";
	}

	if ($smarty) {
		if (users_get_user_id()!=-1) {
			$smarty->assign("users_logged_in", true);
			$smarty->assign("users_global_username", users_get_user_name());
			$smarty->assign("users_username", users_get_user_name());
		} else {
			$smarty->assign("users_logged_in", false);
		}
	}

	return "ok";
}

function users_create_session($user_id) {
	global $users_session_info, $users_session_cookie_name, $users_session_lifetime, $root_path;
	global $auth_cookie_domain;

	$session_id = users_db_create_session($user_id, $_SERVER['REMOTE_ADDR']);
	if ($session_id == "error") {
		return "error";
	}

	setcookie($users_session_cookie_name, $session_id, time() + $users_session_lifetime, $root_path."/", ".$auth_cookie_domain");

	$users_session_info = users_db_get_session_info($session_id);

	return "ok";
}

function users_destroy_session() {
	global $users_session_cookie_name, $users_session_info, $root_path;
	global $auth_cookie_domain;

	setcookie($users_session_cookie_name, "", time() - 3600, $root_path."/", ".$auth_cookie_domain");

	$status = users_db_destroy_session($users_session_info['SESSION_ID_MD5']);
	if ($status == "error") {
		return "error";
	}

	return "ok";
}

function users_get_user_id() {
	global $users_session_info;

	if (!isset($users_session_info['USER_ID'])) {
		return -1;
	}

	return $users_session_info['USER_ID'];
}

// в этой функции у нас будет располагаться механизм вывода имени пользователя на основании его ника, логина, мыла, реального имени и типа авторизации
function users_format_user_name($user_name, $auth_type, $user_nickname, $user_real_name, $user_real_name_flg) {

	if (trim($user_nickname) == "") {
		$name = $user_name;
	} else {
		$name = $user_nickname;
	}

	if ($user_real_name_flg == 0) {
		return $user_real_name . " ($name)";
	} else {
		return $name;
	}
}

function users_get_user_name() {
	global $users_session_info;

	return users_format_user_name($users_session_info['USER_NAME'], $users_session_info['AUTH_TYPE'], $users_session_info['USER_NICKNAME'], $users_session_info['USER_REAL_NAME'], $users_session_info['USER_REAL_NAME_FLG']);
}

function users_file_get_user_password($username) {
	global $users_auth_file_path;

	$all = file($users_auth_file_path);

	foreach ($all as $user_info) {
		list($name, $passhash, $status) = explode(":", trim($user_info));
		if (strtolower($name) == strtolower($username)) {
			return array("ok", $passhash, $status);
		}
	}

	return array("notfound", "", "");
}

function users_save_session_info() {
	global $users_session_info, $users_session_additional_fields;

	$adfields = array();
	foreach ($users_session_additional_fields as $afield) {
		if (isset($users_session_info[$afield])) {
			$adfields[$afield] = addslashes($users_session_info[$afield]);
		}
	}

	$status = users_db_update_session_fields($users_session_info['SESSION_ID'], $adfields);
	if ($status == "error") {
		return "error";
	}

	return "ok";
}

?>