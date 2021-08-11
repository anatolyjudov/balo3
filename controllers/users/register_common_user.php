<?
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;
global $ldap_status_values;

	// если в данный момент пользователь залогинен, то мы попадаем на форму управления его данными
	if (get_user_id()!=-1) {
		// достаем информацию о пользователе
		$user_info = db_user_get_from_common(get_user_id());
		if ($user_info == "error") {
			echo $_ERRORS['DB_ERROR'];
			exit;
		}
		if ($user_info == "notfound") {
			echo $_USERS_ERRORS['NO_SUCH_USER'];
			exit;
		}
		$smarty->assign_by_ref("user_info", $user_info);  
//		show_ar($user_info);

		// выдаем шаблон
		out_main($smarty->fetch("users/edit_common_user_self.tpl"));

	} else {
	// иначе - просто выдаем форму регистрации

		$user_info['AUTH_TYPE'] = 'db';
		$smarty->assign_by_ref("user_info", $user_info);

		// выдаем шаблон
		out_main($smarty->fetch("users/register_common_user.tpl"));

	}


?>