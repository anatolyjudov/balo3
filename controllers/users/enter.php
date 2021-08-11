<?php

require_once("$entrypoint_path/components/users/users_init.php");

do {

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/users/enter.tpl"));

} while (false);

?>



<?php
/*

include($file_path."/includes/users/users_db.php");

global $smarty;
global $params;
global $node_info;

out_main($smarty->fetch("users/enter.tpl"));
*/
?>