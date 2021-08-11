<?

include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info, $root_path;
global $ldap_status_values;
global $domain;

/*	// �����
	if (isset($_POST['user_name'])) {
		$user_info['USER_NAME'] = $_POST['user_name'];
	} else {  
	    $user_info['USER_NAME'] = ""; 
	}    */

	// ��� �����������
	$user_info['AUTH_TYPE'] = 'db';

	// ������
	if ( isset($_POST['user_password']) && isset($_POST['user_password2']) && ($_POST['user_password2'] == $_POST['user_password']) ) {
		$user_info['USER_PASSWORD'] = $_POST['user_password'];
	} else {
		$user_info['USER_PASSWORD'] = "";
	}

	// �������
	/*
	if ( isset($_POST['user_nickname']) && (trim($_POST['user_nickname']) != "") ) {
		$user_info['USER_NICKNAME'] = $_POST['user_nickname'];
	} else {
		$user_info['USER_NICKNAME'] = $user_info['USER_NAME'];
	}
	*/

	// ��������� ���
	if (isset($_POST['user_real_name'])) {
		$user_info['USER_REAL_NAME'] = $_POST['user_real_name'];
	} else {
		$user_info['USER_REAL_NAME'] = "";
	}
	
	// ����� email
	if ( isset($_POST['user_email']) && (trim($_POST['user_email']) != "") and preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", trim($_POST['user_email']))) {
		$user_info['USER_EMAIL'] = $_POST['user_email'];
		$user_info['USER_NAME'] = $_POST['user_email'];
		$user_info['USER_NICKNAME'] = $_POST['user_email'];
	} else {
		$user_info['USER_EMAIL'] = "";
	}	

	// ����� icq
	if (isset($_POST['user_icq'])) {
		$user_info['USER_ICQ'] = $_POST['user_icq'];
	} else {
		$user_info['USER_ICQ'] = "";
	}    
	
	// �������
	if (isset($_POST['user_phone'])) {
		$user_info['USER_PHONE'] = $_POST['user_phone'];
	} else {
		$user_info['USER_PHONE'] = "";
	}	
	
	// ����� ��������
	if (isset($_POST['user_address'])) {
		$user_info['USER_ADDRESS'] = $_POST['user_address'];
	} else {
		$user_info['USER_ADDRESS'] = "";
	}	

	// ���� ������ �����
	if (isset($_POST['user_real_name_flg']) && ($_POST['user_real_name_flg'] == "on")) {
		$user_info['USER_REAL_NAME_FLG'] = 1;
	} else {
		$user_info['USER_REAL_NAME_FLG'] = 0;
	}

	// ���� ������ ����
	if (isset($_POST['user_email_flg']) && ($_POST['user_email_flg'] == "on")) {
		$user_info['USER_EMAIL_FLG'] = 1;
	} else {
		$user_info['USER_EMAIL_FLG'] = 0;
	}

	// ���� ������ �����
	if (isset($_POST['user_icq_flg']) && ($_POST['user_icq_flg'] == "on")) {
		$user_info['USER_ICQ_FLG'] = 1;
	} else {
		$user_info['USER_ICQ_FLG'] = 0;
	}   
	
	// ���� ������ ��������
	if (isset($_POST['user_phone_flg']) && ($_POST['user_phone_flg'] == "on")) {
		$user_info['USER_PHONE_FLG'] = 1;
	} else {
		$user_info['USER_PHONE_FLG'] = 0;
	}	 
	
	// ���� ������ ������
	if (isset($_POST['user_address_flg']) && ($_POST['user_address_flg'] == "on")) {
		$user_info['USER_ADDRESS_FLG'] = 1;
	} else {
		$user_info['USER_ADDRESS_FLG'] = 0;
	}		

	// ����� � ������
	$smarty->assign_by_ref("user_info", $user_info); 
	
/*	// �������� �����
	if($user_info['USER_NAME'] == ""){
		$smarty->assign("errmsg", $_USERS_ERRORS['EMPTY_USER_NAME']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;		
	}    
	if (!preg_match("/^[\w_\-\.\@]+$/", $user_info['USER_NAME'])) {
		$smarty->assign("errmsg", $_USERS_ERRORS['BAD_USER_NAME']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;			
	}   */
	 
	//�������� e-mail
	if($user_info['USER_EMAIL'] == ""){
		$smarty->assign("errmsg", $_USERS_ERRORS['BAD_EMAIL']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;		
	}	

	// ���� � ����� ���� �������������
	$test_user_info = db_user_get_from_common($user_info['USER_NAME']);
	if ($test_user_info == "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}
	// �������� �� ������������ �����
	if ($test_user_info != "notfound") {
		$smarty->assign("errmsg", $_USERS_ERRORS['USER_EXISTS']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}

	// �������� ������������ ���������� ��������
	$status = db_user_common_nickname_exists($user_info['USER_NICKNAME']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}
	if ($status) {
		$smarty->assign("errmsg", $_USERS_ERRORS['NICKNAME_EXISTS']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}      
	
	// �������� ������������ email
	$status = db_users_common_email_exists($user_info['USER_EMAIL']);
	if ($status === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}
	if ($status) {
		$smarty->assign("errmsg", $_USERS_ERRORS['EMAIL_EXISTS']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}	

	// �������� �������
	if ($user_info['USER_PASSWORD'] == "") {
		$smarty->assign("errmsg", $_USERS_ERRORS['WRONG_PASSWORD']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	}       
	
	// �������� ������������� ����
	$activation_id = md5(mt_rand());
	
	// ������� ������������
	$user_id = db_users_create_user_common($user_info['USER_NAME'], $user_info['AUTH_TYPE'], $user_info['USER_PASSWORD'], $user_info['USER_NICKNAME'], $user_info['USER_REAL_NAME'], $user_info['USER_EMAIL'], $user_info['USER_ICQ'], $user_info['USER_PHONE'], $user_info['USER_ADDRESS'], $user_info['USER_REAL_NAME_FLG'], $user_info['USER_EMAIL_FLG'], $user_info['USER_ICQ_FLG'], $user_info['USER_PHONE_FLG'], $user_info['USER_ADDRESS_FLG'], $activation_id);
	if ($user_id === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("users/register_common_user.tpl"));
		exit;
	} 
	 
	$subj = "���������� �����������";
	$subj = "=?windows-1251?b?" . base64_encode(trim($subj)) . "?=";
	$body = '��� ���������� ���������� ��������� �� ������ '.$domain.$root_path.'/login/register/activate/?aid='.$activation_id;
	$headers = "From: robot@iklmn.ru\n";
	$headers .= "Content-Type: text/plain; charset=\"windows-1251\"\n";  
	
	mail($user_info['USER_EMAIL'], $subj, $body, $headers);
    //echo '��� ���������� ���������� ��������� �� ������ '.$domain.'/login/register/activate/?aid='.$activation_id; 
    
	// ������ ������
	out_main($smarty->fetch("users/proceed_register_common_user.tpl"));
?>