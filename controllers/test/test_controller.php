<?php

require_once("$entrypoint_path/components/test/test_init.php");

do {

	$str = "this is just a test controller's output";

	//echo balo3_text("test", "TEST_MESSAGE");

	//show_ar($meta_info);

	balo3_controller_output($smarty->fetch("$templates_path/test/test_controller.tpl"));

} while (false);

?>