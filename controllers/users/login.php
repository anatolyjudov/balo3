<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	$delete = users_db_delete_notactivated(); 
	if ($delete === "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}

	if (isset($_POST['return_path'])) {
		$smarty->assign("login_return_path", $_POST['return_path']);
	}

	// проверка пришедших логина и пароля
	if (array_key_exists("login", $_POST) && ($_POST['login']!="") ) {
		$login = $_POST['login'];
	}

	if (array_key_exists("pass", $_POST) && ($_POST['pass']!="") ) {
		$pass = $_POST['pass'];
	}

	// проверяем логин на соответствие
	if (!preg_match("/^[\w_\-\.\@]+$/", $login)) {
		// обработка ошибки
		balo3_error($_USERS_ERRORS['BAD_USER_NAME'], true);
		exit;
	}

	// ищем в базе пользователей
	$user_info = users_db_get_user_info($login);
	if ($user_info === "error") {
		// обработка ошибки
		balo3_error("db error", true);
		exit;
	}
	if ($user_info === "notfound") {
		// обработка ошибки
		balo3_error($_USERS_ERRORS['NO_SUCH_USER'], true);
		exit;
	}

	// проверяем введенный пароль
	if ($user_info['AUTH_TYPE'] == 'db') {
		if($user_info['ACTIVE'] == 1){
			if ( md5($pass) == $user_info['USER_PASSWORD']) {
				$login_status = "ok";
				users_create_session($user_info['USER_ID']);
			} else {
				// обработка ошибки
				balo3_error($_USERS_ERRORS['WRONG_PASSWORD'], true);
				exit;
			}
		} else {
			// обработка ошибки
			balo3_error($_USERS_ERRORS['USER_NOT_ACTIVE'], true);
			exit;
		}
	}

	$session_info[$store_basket_field] = $temp_basket;

	$status = users_save_session_info();
	if ($status === "error") {
		return "error";
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/users/login.tpl"));

} while (false);

?>


<?php
/*
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");
include($file_path."/includes/users/users_config.php");

global $smarty;
global $params;
global $node_info;
global $ldap_status_values;
global $store_basket_field;	

	$delete = db_users_delete_notactivated(); 
	if ($delete === "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	if (isset($_POST['return_path'])) {
		$smarty->assign("login_return_path", $_POST['return_path']);
	}

	// проверка пришедших логина и пароля
	if (array_key_exists("login", $_POST) && ($_POST['login']!="") ) {
		$login = $_POST['login'];
	}

	if (array_key_exists("pass", $_POST) && ($_POST['pass']!="") ) {
		$pass = $_POST['pass'];
	}

	// проверяем логин на соответствие
	if (!preg_match("/^[\w_\-\.\@]+$/", $login)) {
		// обработка ошибки
		echo $_USERS_ERRORS['BAD_USER_NAME'];
		exit;
	}

	// ищем в общей базе пользователей
	$user_info = db_user_get_from_common($login);
	if ($user_info == "error") {
		// обработка ошибки
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	$ldap_new_user = false;

	// если нет:
	if ($user_info === "notfound") {

		// Поскольку на этом сайте никакого LDAP'а мы не используем, то просто сообщаем что пользователя не существует.
		// обработка ошибки
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;

	}  
	               
	// сожеджимое корзины у незалогиненного пользователя
	$temp_basket = $session_info[$store_basket_field];	

	// ---
	// проверяем введенный пароль

	if ($user_info['AUTH_TYPE'] == "ldap") {

		if (!$ldap_new_user) {

			// спрашиваем в ldap'е
			//list($status, $user_real_pass, $user_active) = ldap_get_user_password($login);
			
			$status = "ok";
			$user_real_pass = "mega";
			$user_active = "active";
			
			// не было ли ошибки?
			if ($status == "error") {
				// обработка ошибки
				echo $_USERS_ERRORS['LDAP_ERROR'];
				exit;
			}
			// нашелся пользователь?
			if ($status == "notfound") {
				// обработка ошибки
				echo $_USERS_ERRORS['NO_SUCH_USER'];
				exit;
			}
			// активен?
			if ($user_active != $ldap_status_values["active"]) {
				// обработка ошибки
				echo $_USERS_ERRORS['INACTIVE_USER'];
				exit;
			}

			$user_id = $user_info['USER_ID'];

		}

		if ( $pass == $user_real_pass ) {
			#echo "creating session% $user_id";
			$login_status = "ok";
			create_session($user_id);
		} else {
			// обработка ошибки
			echo $_USERS_ERRORS['WRONG_PASSWORD'];
			exit;
		}

	}

	if ($user_info['AUTH_TYPE'] == 'db') {
		if($user_info['ACTIVE'] == 1){
			if ( md5($pass) == $user_info['USER_PASSWORD']) {
				$login_status = "ok";
				create_session($user_info['USER_ID']);
			} else {
				// обработка ошибки
				echo $_USERS_ERRORS['WRONG_PASSWORD'];
				exit;
			}
		} else {
			// обработка ошибки
			echo $_USERS_ERRORS['USER_NOT_ACTIVE'];
			exit;
		}
	}

	$session_info[$store_basket_field] = $temp_basket;

	$status = save_session_info();
	if ($status == "error") {
		return "error";
	}    
    
	$smarty->display("users/login.tpl");
*/
?>
