<?
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;
global $ldap_status_values;

	if (get_user_id() == -1) {
		error404();
		exit;
	}

	$user_id = get_user_id();

	// достаем информацию о пользователе
	$old_user_info = db_user_get_from_common($user_id);
	if ($old_user_info == "error") {
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($old_user_info == "notfound") {
		echo $_USERS_ERRORS['NO_SUCH_USER'];
		exit;
	}
	$smarty->assign_by_ref("old_user_info", $old_user_info);

	$user_info['USER_ID'] = $old_user_info['USER_ID'];
	$user_info['AUTH_TYPE'] = $old_user_info['AUTH_TYPE'];
	$user_info['USER_NAME'] = $old_user_info['USER_NAME'];
	$user_info['USER_NICKNAME'] = $old_user_info['USER_NICKNAME'];

	// пароль
	if ( isset($_POST['user_password']) && isset($_POST['user_password2']) && ($_POST['user_password2'] == $_POST['user_password']) ) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// никнейм
	/*
	if ( isset($_POST['user_nickname']) && (trim($_POST['user_nickname']) != "") ) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}
	*/

	// настоящее имя
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}

	// адрес email
	if (isset($_POST['user_email'])) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}

	// адрес icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}    
	
	// телефон
	if (isset($_POST['user_phone'])) {
		$user_info['USER_PHONE'] = $_POST['user_phone'];
	} else {
		$user_info['USER_PHONE'] = "";
	}	
	
	// адрес доставки
	if (isset($_POST['user_address'])) {
		$user_info['USER_ADDRESS'] = $_POST['user_address'];
	} else {
		$user_info['USER_ADDRESS'] = "";
	}		

	// флаг показа имени
	if (isset($_POST['user_real_name_flg']) && ($_POST['user_real_name_flg'] == "on")) {
		$user_info['USER_REAL_NAME_FLG'] = 1;
	} else {
		$user_info['USER_REAL_NAME_FLG'] = 0;
	}

	// флаг показа мыла
	if (isset($_POST['user_email_flg']) && ($_POST['user_email_flg'] == "on")) {
		$user_info['USER_EMAIL_FLG'] = 1;
	} else {
		$user_info['USER_EMAIL_FLG'] = 0;
	}

	// флаг показа аськи
	if (isset($_POST['user_icq_flg']) && ($_POST['user_icq_flg'] == "on")) {
		$user_info['USER_ICQ_FLG'] = 1;
	} else {
		$user_info['USER_ICQ_FLG'] = 0;
	}  
		
	// флаг показа телефона
	if (isset($_POST['user_phone_flg']) && ($_POST['user_phone_flg'] == "on")) {
		$user_info['USER_PHONE_FLG'] = 1;
	} else {
		$user_info['USER_PHONE_FLG'] = 0;
	}	 
	
	// флаг показа адреса
	if (isset($_POST['user_address_flg']) && ($_POST['user_address_flg'] == "on")) {
		$user_info['USER_ADDRESS_FLG'] = 1;
	} else {
		$user_info['USER_ADDRESS_FLG'] = 0;
	}	

	// кладём в смарти
	$smarty->assign_by_ref("user_info", $user_info);

	// проверка

	// проверим уникальность выбранного никнейма
	if ($user_info['USER_NICKNAME'] != $old_user_info['USER_NICKNAME']) {
		$status = db_user_common_nickname_exists($user_info['USER_NICKNAME']);
		if ($status === "error") {
			$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("users/edit_common_user_self.tpl"));
			exit;
		}
		if ($status) {
			$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
			out_main($smarty->fetch("users/edit_common_user_self.tpl"));
			exit;
		}
	}

	// изменяем данные пользователя
	$status = db_users_modify_user_common($user_info['USER_ID'], $user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], $user_info['USER_PHONE'], $user_info['USER_ADDRESS'], $user_info['USER_REAL_NAME_FLG'], $user_info['USER_EMAIL_FLG'], $user_info['USER_ICQ_FLG'], $user_info['USER_PHONE_FLG'], $user_info['USER_ADDRESS_FLG']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/edit_common_user_self.tpl"));
		exit;
	}

	// выдаем шаблон
	out_main($smarty->fetch("users/modify_common_user_self.tpl"));
?>