<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/new_user.tpl"));

} while (false);

?>



<?php
/*
include($file_path."/includes/users/users_config.php");
include($file_path."/includes/users/users_db.php");
include($file_path."/includes/users/users_texts.php");

global $smarty;
global $params;
global $node_info;
global $ldap_status_values;

	// ������ ������
	out_main($smarty->fetch("users/new_common_user.tpl"));
*/
?>