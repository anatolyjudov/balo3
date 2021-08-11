<?php

$users_admin_on_page = 20;

// когда удалять, тех кто не активировался
$users_noactivated_days = 5;


# таблицы
$users_users_table = "USERS_USERS";
$users_roles_table = "USERS_ROLES";
$users_actions_table = "USERS_ACTIONS";
$users_roles_actions_table = "USERS_R_ROLES_ACTIONS";
$users_users_roles_table = "USERS_R_USERS_ROLES";
$users_sessions_table = "USERS_SESSIONS";

$users_session_lifetime = 604800;		// одна неделя
$users_session_info = array();
$users_session_cookie_name = "balo3session2cookie";
$users_session_additional_fields = array("SESSION_DATA");

$auth_cookie_domain = $common_simple_domain;
$auth_cookie_domain_logout = $auth_cookie_domain;

?>
