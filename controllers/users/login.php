<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	$delete = users_db_delete_notactivated(); 
	if ($delete === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}

	if (isset($_POST['return_path'])) {
		$smarty->assign("login_return_path", $_POST['return_path']);
	}

	// �������� ��������� ������ � ������
	if (array_key_exists("login", $_POST) && ($_POST['login']!="") ) {
		$login = $_POST['login'];
	}

	if (array_key_exists("pass", $_POST) && ($_POST['pass']!="") ) {
		$pass = $_POST['pass'];
	}

	// ��������� ����� �� ������������
	if (!preg_match("/^[\w_\-\.\@]+$/", $login)) {
		// ��������� ������
		balo3_error($_USERS_ERRORS['BAD_USER_NAME'], true);
		exit;
	}

	// ���� � ���� �������������
	$user_info = users_db_get_user_info($login);
	if ($user_info === "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($user_info === "notfound") {
		// ��������� ������
		balo3_error($_USERS_ERRORS['NO_SUCH_USER'], true);
		exit;
	}

	// ��������� ��������� ������
	if ($user_info['AUTH_TYPE'] == 'db') {
		if($user_info['ACTIVE'] == 1){
			if ( md5($pass) == $user_info['USER_PASSWORD']) {
				$login_status = "ok";
				users_create_session($user_info['USER_ID']);
			} else {
				// ��������� ������
				balo3_error($_USERS_ERRORS['WRONG_PASSWORD'], true);
				exit;
			}
		} else {
			// ��������� ������
			balo3_error($_USERS_ERRORS['USER_NOT_ACTIVE'], true);
			exit;
		}
	}

	$session_info[$store_basket_field] = $temp_basket;

	$status = users_save_session_info();
	if ($status === "error") {
		return "error";
	}

	// ������ ������
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
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	if (isset($_POST['return_path'])) {
		$smarty->assign("login_return_path", $_POST['return_path']);
	}

	// �������� ��������� ������ � ������
	if (array_key_exists("login", $_POST) && ($_POST['login']!="") ) {
		$login = $_POST['login'];
	}

	if (array_key_exists("pass", $_POST) && ($_POST['pass']!="") ) {
		$pass = $_POST['pass'];
	}

	// ��������� ����� �� ������������
	if (!preg_match("/^[\w_\-\.\@]+$/", $login)) {
		// ��������� ������
		echo $_USERS_ERRORS['BAD_USER_NAME'];
		exit;
	}

	// ���� � ����� ���� �������������
	$user_info = db_user_get_from_common($login);
	if ($user_info == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}

	$ldap_new_user = false;

	// ���� ���:
	if ($user_info === "notfound") {

		// ��������� �� ���� ����� �������� LDAP'� �� �� ����������, �� ������ �������� ��� ������������ �� ����������.
		// ��������� ������
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;

	}  
	               
	// ���������� ������� � ��������������� ������������
	$temp_basket = $session_info[$store_basket_field];	

	// ---
	// ��������� ��������� ������

	if ($user_info['AUTH_TYPE'] == "ldap") {

		if (!$ldap_new_user) {

			// ���������� � ldap'�
			//list($status, $user_real_pass, $user_active) = ldap_get_user_password($login);
			
			$status = "ok";
			$user_real_pass = "mega";
			$user_active = "active";
			
			// �� ���� �� ������?
			if ($status == "error") {
				// ��������� ������
				echo $_USERS_ERRORS['LDAP_ERROR'];
				exit;
			}
			// ������� ������������?
			if ($status == "notfound") {
				// ��������� ������
				echo $_USERS_ERRORS['NO_SUCH_USER'];
				exit;
			}
			// �������?
			if ($user_active != $ldap_status_values["active"]) {
				// ��������� ������
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
			// ��������� ������
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
				// ��������� ������
				echo $_USERS_ERRORS['WRONG_PASSWORD'];
				exit;
			}
		} else {
			// ��������� ������
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
