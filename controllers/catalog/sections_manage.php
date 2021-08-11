<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	// גûהאול ראבכמם
	balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage.tpl"));

} while (false);

?>