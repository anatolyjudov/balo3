<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// �������� �� ������ ������ � ������������
	users_destroy_session();

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/users/logout.tpl"));

} while (false);

?>

<?php
/*
include($file_path."/includes/users/users_db.php");

global $smarty;
global $params;
global $node_info;

	// �������� �� ������ ������ � ������������
	destroy_session();

	$smarty->display("users/logoff.tpl");
*/
?>