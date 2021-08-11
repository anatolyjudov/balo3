<?
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;

	// никнейм
	if ( isset($_POST['login_or_mail'])) {      
		if(trim($_POST['login_or_mail']) == ''){
			$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_REQUEST']);
			out_main($smarty->fetch("users/recovery_pass.tpl"));
			exit;			
		}
		$user_info = db_users_recovery_pass($_POST['login_or_mail']);
		if($user_info == 'error'){
			$smarty->assign("errmsg", $_USERS_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("users/recovery_pass.tpl"));
			exit;			
		}    
		if($user_info == 'notfound'){
			$smarty->assign("errmsg", $_USERS_ERRORS['NOTHING_FOR_REQUEST']);
			out_main($smarty->fetch("users/recovery_pass.tpl"));
			exit;			
		}
		if($user_info['USER_EMAIL'] == '')	{
			$smarty->assign("errmsg", $_USERS_ERRORS['NO_EMAIL']);
			out_main($smarty->fetch("users/recovery_pass.tpl"));
			exit;			
		} else{
			$status = db_users_send_new_pass_for_email($user_info['USER_ID'], $user_info['USER_EMAIL']);
			if($status == 'error'){
				$smarty->assign("errmsg", $_USERS_ERRORS['DB_ERROR']);
				out_main($smarty->fetch("users/recovery_pass.tpl"));
				exit;			
			}   
			out_main($smarty->fetch("users/recovery_pass_send.tpl")); 			
		}
	} else {
		out_main($smarty->fetch("users/recovery_pass.tpl"));
	}    
	 

?>